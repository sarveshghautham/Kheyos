package ServerEngine

import java.util.concurrent.LinkedBlockingDeque

import scala.actors.threadpool.LinkedBlockingQueue
import scala.collection.mutable.ListBuffer

//object db {
//
//  // Change to Your Database Config
//
//  //dbObj.selectQuery
//}
import java.sql.{Connection, DriverManager, ResultSet}

class DbCon {


//  val dbObj = new DbCon()
//  dbObj.connect

  val url = "jdbc:mysql://162.253.224.4:3306/kheyosco_kheyos?user=kheyosco_db&password=Kheyos2026"
  var conn : Connection = null

  def connect = {
    // Load the driver
    Class.forName("com.mysql.jdbc.Driver").newInstance
    //Class.forName("com.mysql.jdbc.Driver");

    // Setup the connection
    conn = DriverManager.getConnection(url)
  }

  def selectQuery = {
    try {
      // Configure to be Read Only
      val statement = conn.createStatement(ResultSet.TYPE_FORWARD_ONLY, ResultSet.CONCUR_READ_ONLY)

      // Execute Query
      val rs = statement.executeQuery("SELECT * FROM Status")

      // Iterate Over ResultSet
      while (rs.next) {
        println(rs.getString("text"))
      }
    }
    finally {
      conn.close
    }
  }

  def selectQuery(query : String): ResultSet = {
    try {
      // Configure to be Read Only
      val statement = conn.createStatement(ResultSet.TYPE_FORWARD_ONLY, ResultSet.CONCUR_READ_ONLY)
      // Execute Query
      val rs = statement.executeQuery(query)
      return rs;
    }
    finally {
      //conn.close
    }
  }


  //Test method
  def dbGetStatus(avatarId : Int) : ListBuffer[Status] = {
    try {
      // Configure to be Read Only
      val statement = conn.createStatement(ResultSet.TYPE_FORWARD_ONLY, ResultSet.CONCUR_READ_ONLY)
      val query : String = "SELECT * FROM Status WHERE avatar_id="+avatarId
      // Execute Query
      val rs = statement.executeQuery(query)

      var result : ListBuffer[Status] = ListBuffer.empty[Status]

      // Iterate Over ResultSet
      while (rs.next) {
        result += new Status(rs.getInt("status_id"), rs.getInt("avatar_id"), rs.getInt("picture_id"), rs.getBoolean("active"), rs.getString("text"), rs.getTimestamp("time"))
      }

      return result
    }
    finally {
      conn.close
    }
  }

  def dbGetFollowers (avatarId : Int): ListBuffer[Int] = {
    try {
      // Configure to be Read Only

      val query : String = "SELECT * FROM Follow WHERE avatar_id_1="+avatarId
      // Execute Query
      val rs = selectQuery(query)

      var result : ListBuffer[Int] = ListBuffer.empty[Int]

      // Iterate Over ResultSet
      while (rs.next) {
        result += rs.getInt("avatar_id_2")
      }

      return result
    }
    finally {
      conn.close
    }
  }

  def getOneStatus (statusId : Int) : Status = {
    val query = "SELECT * FROM Status WHERE status_id="+statusId;
    val rs = selectQuery(query)
    var statusObj : Status = null
    while (rs.next) {
      val statusId = rs.getInt("status_id")
      statusObj = new Status(statusId, rs.getInt("avatar_id"), rs.getInt("""picture_id"""), rs.getBoolean("active"), rs.getString("text"), rs.getTimestamp("time"))
      if (!ServerActor.statusMap.contains(statusId))
        ServerActor.statusMap += (statusId -> statusObj)
    }

    return statusObj;
  }

  def dbNewsFeed (avatarId : Int) : LinkedBlockingQueue[Int] = {
    val statusQueue : LinkedBlockingQueue[Int] = new LinkedBlockingQueue[Int]
    val query = "SELECT * FROM Status WHERE avatar_id IN (SELECT avatar_id_2 FROM Follow WHERE avatar_id_1 ="+avatarId+") ORDER BY time DESC LIMIT 100";

    val rs = selectQuery(query)

    while (rs.next) {
      val statusId = rs.getInt("status_id")
      val statusObj = new Status(statusId, rs.getInt("avatar_id"), rs.getInt("""picture_id"""), rs.getBoolean("active"), rs.getString("text"), rs.getTimestamp("time"))
      if (!ServerActor.statusMap.contains(statusId))
        ServerActor.statusMap += (statusId -> statusObj)
      statusQueue.offer(statusId)
    }

    return statusQueue
  }

}