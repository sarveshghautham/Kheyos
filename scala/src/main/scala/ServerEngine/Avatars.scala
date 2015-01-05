package ServerEngine
import scala.actors.threadpool.LinkedBlockingQueue
import scala.collection.mutable.ListBuffer

/**
 * Created by sarvesh on 12/29/14.
 */
class Avatars(avatar_id : Int) {

  private val avatarId : Int = avatar_id
  private var followers : ListBuffer[Int] = ListBuffer.empty[Int]
  private val messageQueue = new LinkedBlockingQueue[Int](100)
  private var activeStatus : Int = 0


  def getFollowers : ListBuffer[Int] = {
    return followers
  }

  def getMessages : List[Int] = {
    return messageQueue.toArray().toList.asInstanceOf[List[Int]]
  }

  def addFollower (followerId : Int) = {
    followers += followerId
  }

  def deleteFollower(avatarId : Int) = {
    followers -= avatarId
  }

  def addStatusToQueue(statusId : Int) =  {

    if (messageQueue.size() >= 100)
      messageQueue.poll()

    //Put in the queue
    messageQueue.offer(statusId)
    setMyActiveStatus(statusId)
  }

  def getMyActiveStatus : Int = {
    return activeStatus
  }

  def setMyActiveStatus(statusId : Int) = {
     activeStatus = statusId
  }

}
