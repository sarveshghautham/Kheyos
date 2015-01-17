package ServerEngine

import ServerEngine.Messages.GetActiveStatus

import scala.actors.threadpool.LinkedBlockingQueue
import scala.collection.mutable.ListBuffer

/**
 * Created by sarvesh on 12/29/14.
 */
class Avatars(avatar_id : Int, followersList : ListBuffer[Int], filledStatusQueue : LinkedBlockingQueue[Int], activeStatusId: Int) {

  private val avatarId : Int = avatar_id
  private var followers : ListBuffer[Int] = followersList
  private val statusQueue = filledStatusQueue
  private var activeStatus : Int = activeStatusId

  def getFollowers : ListBuffer[Int] = {
    return followers
  }

  def getMessages : List[Int] = {
    return statusQueue.toArray().toList.asInstanceOf[List[Int]]
  }

  def addFollower (followerId : Int) = {
    followers += followerId
  }

  def deleteFollower(avatarId : Int) = {
    followers -= avatarId
  }

  def addStatusToQueue(statusId : Int) =  {

    if (statusQueue.size() >= 100)
      statusQueue.poll()

    //Put in the queue
    statusQueue.offer(statusId)
    setMyActiveStatus(statusId)
  }

  def getMyActiveStatus : Int = {
    return activeStatus
  }

  def setMyActiveStatus(statusId : Int) = {
     activeStatus = statusId
  }

}
