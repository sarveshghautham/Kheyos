package ServerEngine
import java.sql.Timestamp
import java.util.Date
import java.util.concurrent.LinkedBlockingDeque

import Messages._
import akka.actor.{Actor, Props, ActorSystem, ActorRef}
import akka.routing.RoundRobinRouter
import spray.routing.SimpleRoutingApp

import org.json4s.{DefaultFormats, Formats}
import org.json4s.JsonAST.JObject
import spray.httpx.Json4sSupport
import spray.routing._
import spray.http.MediaTypes
import scala.actors.threadpool.LinkedBlockingQueue
import scala.concurrent.duration._
import akka.util.Timeout
import scala.collection.mutable.ListBuffer
import scala.util.Random
import akka.pattern.ask

object Server extends App with SimpleRoutingApp with Json4sSupport {
  implicit val system = ActorSystem("KheyosServer")
  implicit val serverActor =  system.actorOf(Props[ServerActor].withRouter(RoundRobinRouter(10)) , name = "ServerActor")

  implicit val timeout = Timeout(1.second)
  import system.dispatcher
  implicit def json4sFormats: Formats = DefaultFormats

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
        parameters("avatar_id".as[Int]) {
          (avatarId) =>
            (serverActor ? Login(avatarId))
            complete {
              "OK"
            }
        }
      }
    } ~
    post {
      path("post" / "logout") {
        parameters("avatar_id".as[Int]) {
          (avatarId) =>
            (serverActor ? Logout(avatarId))
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
    } ~
    post {
      path("post_status"){
        entity(as[StatusJSON]) { statusObj =>
          //val status = statusObj.extract[Status]
          serverActor ? PostStatus(statusObj.avatarId, statusObj.pictureId, statusObj.status)
          complete {
            "OK"
          }
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
  //DB Connection
  val db : DbCon = new DbCon()

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

    val avatarObj = ServerActor.avatarMap(avatarId)
    avatarObj.addStatusToQueue(statusId)

    //Get my followers
    val myFollowers = avatarObj.getFollowers
    //Add the status to all the followers queue
    for (followerAvatarId <- myFollowers) {
      if (!ServerActor.avatarMap.contains(followerAvatarId)) {
        Login(followerAvatarId)
      }

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
    //ServerActor.db.connect
    var avatarObj = ServerActor.avatarMap(avatarId)
    val statusIds = avatarObj.getMessages
    var statusList : ListBuffer[Status] = ListBuffer.empty[Status]
    for (statusId <- statusIds) {
      if (ServerActor.statusMap.contains(statusId)) {
        statusList += ServerActor.statusMap(statusId)
      }
      else {
        val tempDb = ServerActor.db
        tempDb.connect
        statusList += tempDb.getOneStatus(statusId)
      }
    }
    return statusList
  }

  def Login(avatarId : Int) = {
    println("Login => UserID is: "+avatarId)

    val tempDb = ServerActor.db
    tempDb.connect
    val followers = tempDb.dbGetFollowers(avatarId)

    tempDb.connect
    val statusQueue : LinkedBlockingQueue[Int] = tempDb.dbNewsFeed(avatarId)
    val avatarObj = new Avatars(avatarId, followers, statusQueue, 0)

    if (!ServerActor.avatarMap.contains(avatarId)) {
      ServerActor.avatarMap += (avatarId -> avatarObj)
    }

  }

  def Logout(avatarId : Int) = {
    ServerActor.avatarMap -= avatarId
  }

}
