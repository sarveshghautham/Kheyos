/**
 * Created by sarvesh on 1/1/15.
 */
package ServerEngine
object Messages {

  sealed trait KheyosMessage
  case class Login(userId : Int) extends  KheyosMessage
  case class Logout(userId : Int) extends KheyosMessage
  case class PostStatus (avatarId : Int, pictureId : Int, status : String) extends KheyosMessage
  case class GetActiveStatus(avatarId : Int) extends KheyosMessage
  case class GetAllMyStatuses(avatarId : Int) extends KheyosMessage
  case class GetMyFeed(avatarId : Int) extends KheyosMessage
  case class addFollower(avatarId1 : Int, avatarId2 : Int) extends KheyosMessage
  case class deleteFollower(avatarId1 : Int, avatarId2 : Int) extends KheyosMessage
}
