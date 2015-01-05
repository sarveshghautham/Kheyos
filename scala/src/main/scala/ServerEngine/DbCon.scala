package ServerEngine
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



}