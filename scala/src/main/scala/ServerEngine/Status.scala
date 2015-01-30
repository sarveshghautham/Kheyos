package ServerEngine
import java.sql.Timestamp

/**
 * Created by sarvesh on 12/29/14.
 */
class Status(status_id : Int, avatar_id : Int, picture_id : Int, active_post : Boolean, status_text : String, time_stamp : Timestamp) {

  private var statusId : Int = status_id
  private var avatarId : Int = avatar_id
  private var pictureId : Int = picture_id
  private var active : Boolean = active_post
  private val statusText : String = status_text
  private val timestamp : Timestamp = time_stamp

  def getStatusText : String = {
    return statusText
  }

  def getStatusId : Int = {
    return statusId
  }

  def getAvatarId : Int = {
    return avatarId
  }

  def getPictureId : Int = {
    return pictureId
  }

  def getTimestamp : Timestamp = {
    return timestamp
  }

  def getActive : Boolean = {
    return active
  }

}
