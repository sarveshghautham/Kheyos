package ServerEngine
import java.sql.Timestamp
import java.util.Date

import Messages._
import akka.actor.{Actor, Props, ActorSystem, ActorRef}
import akka.routing.RoundRobinRouter
import spray.routing._
import spray.http.MediaTypes
import scala.concurrent.duration._
import akka.util.Timeout
import scala.collection.mutable.ListBuffer
import scala.util.Random
import akka.pattern.ask

object Server extends App with SimpleRoutingApp {
  implicit val system = ActorSystem("KheyosServer")
  implicit val serverActor =  system.actorOf(Props[ServerActor].withRouter(RoundRobinRouter(10)) , name = "ServerActor")
  implicit val timeout = Timeout(1.second)
  import system.dispatcher

  def getJson(route : Route) = get {
    respondWithMediaType(MediaTypes.`application/json`) { route }
  }

  lazy val serverRoute = {
    get {
      path("get" / "test") {
        complete {
          (serverActor ? "hello").mapTo[String].map(s => ServerActor.toJson(s))
        }
      }
    } ~
    post {
      path("post" / "login") {
        parameters("user_id".as[Int]) {
          (userId) =>
          (serverActor ? Login(userId))
          complete {
            "OK"
          }
        }
      }
    } ~
    post {
      path("post" / "add") {
        parameters("avatarId".as[Int], "pictureId".as[Int], "status") {
          (avatarId, pictureId, status) =>
          (serverActor ? PostStatus(avatarId, pictureId, status))
          complete {
            "OK"
          }
        }
      }
    } ~
    get {
      path("get" / "feed" / IntNumber) { avatarId =>
        complete {
          (serverActor ? GetMyFeed(avatarId)).mapTo[ListBuffer[Status]].map(s => ServerActor.toJson(s))
        }
      }
    } ~
    get {
      path("get" / "status" / IntNumber) { avatarId =>
        complete {
          (serverActor ? GetActiveStatus(avatarId)).mapTo[Status].map(s => ServerActor.toJson(s))
        }
      }
    } ~
    get {
      path("get" / "all" / IntNumber) { avatarId =>
        complete {
          (serverActor ? GetAllMyStatuses(avatarId)).mapTo[ListBuffer[Status]].map(s => ServerActor.toJson(s))
        }
      }
    }
  }

  startServer(interface = "localhost", port = 8180) {
    serverRoute
  }

}

object ServerActor {

  var randomGenerator = new Random()
  //Avatar ID and Avatar Object mapping
  var avatarMap : Map[Int, Avatars] = Map()
  //Status ID and status object mapping
  var statusMap : Map[Int, Status] = Map()

  import org.json4s.native.Serialization.{writePretty}
  import org.json4s.{ FieldSerializer, DefaultFormats }
  private implicit val formats = DefaultFormats + FieldSerializer[Status]()
  def toJson(result : String) : String = writePretty(result)
  def toJson(statuses : ListBuffer[Status]) : String = writePretty(statuses)
  def toJson(status : Status) : String = writePretty(status)

}

class ServerActor extends Actor {

  def receive = {

    case "hello" =>
      sender ! "Received a hello message"

    case Login(userId) =>
      Login(userId)

    case Logout(userId) =>
      Logout(userId)

    case PostStatus(avatarId, pictureId, status) =>
      postStatus(avatarId, pictureId, status)

    case GetActiveStatus(avatarId) =>
      sender ! ServerActor.statusMap(ServerActor.avatarMap(avatarId).getMyActiveStatus)

    case GetAllMyStatuses(avatarId) =>
      sender ! getAllMyStatuses(avatarId)

    case GetMyFeed(avatarId) =>
      sender ! getMyFeed(avatarId)

    case addFollower(avatarId1, avatarId2) =>
      val myAvatarObj = ServerActor.avatarMap(avatarId1)
      myAvatarObj.addFollower(avatarId2)

    case deleteFollower(avatarId1, avatarId2) =>
      val myAvatarObj = ServerActor.avatarMap(avatarId1)
      myAvatarObj.deleteFollower(avatarId2)

    case _ =>
      sender ! "Default case"

  }


  def postStatus(avatarId : Int, pictureId : Int, statusText : String) = {

    val r = ServerActor.randomGenerator
    val statusId = r.nextInt()
    val active : Boolean = true

    //Created a status
    val statusObj = new Status(statusId, avatarId, pictureId, active, statusText, new Timestamp(new Date().getTime()))

    ServerActor.statusMap += (statusId -> statusObj)

    //Now add the status to the message queue
    //TODO: Add a if condition. If the avatarId is not already present in the
    //avatarMap, go and fetch from the DB
    val avatarObj = ServerActor.avatarMap(avatarId)
    avatarObj.addStatusToQueue(statusId)

    //Get my followers
    val myFollowers = avatarObj.getFollowers

    //Add the status to all the followers queue
    for (followerAvatarId <- myFollowers) {
      val followerObj = ServerActor.avatarMap(followerAvatarId)
      followerObj.addStatusToQueue(statusId)
    }

  }

  //My news feed
  def getMyFeed(avatarId : Int) : ListBuffer[Status] = {
    var statuses : ListBuffer[Status] = ListBuffer.empty[Status]
    val avatarObj = ServerActor.avatarMap(avatarId)
    val tempList : List[Int] = avatarObj.getMessages

    for (statusIds <- tempList) {

      statuses += ServerActor.statusMap(statusIds)
    }

    return statuses
  }

  def getAllMyStatuses(avatarId : Int) : ListBuffer[Status] = {

    //TODO: Fetch avatarObj and do. This is only for test purpose
    val db : DbCon = new DbCon()
    db.connect
    val res = db.dbGetStatus(avatarId)
    return res

  }

  def Login(userId : Int) = {
    println("Login => UserID is: "+userId)
  }

  def Logout(userId : Int) = {

  }

}
