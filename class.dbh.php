<?php
//ini_set("mysql.default_socket","/home/wisp/tmp/mysql.sock");
 /**
     * DBH Class - Provides all MySQL database functions used in this package
     *
     * <p>This class implements an object oriented wrapper around the bult in 
     * MySQL functions shiped with PHP in a way that could be extended into an
     * abstraction layer to handle Postgres, ODBS e.t.c</P>
     *
     * @author Peter Lorimer <peter@oslo.ie>
     * @license Interleaf
     * @copyright Interleaf Technology Ltd
     *
     * Date: 22 July 2011
     */

class DBH {
/**
 * Class DBH
 * @package DBH
 *
 * Provides an object orineted wrapper around the built in mysql functions
 *
 */
private $user, $pass, $host, $db;


/**
 * Constructor function
 *
 * Constructs an instance of the DBH class to allow the database to be manipulated
 *
 * @param  string    $host - The database host
 * @param  string    $user - The database user
 * @param  string    $pass - The database password
 * @param  string    $db   - The database name
 * @return resource  $conn - A mysql result resource
*/ 
public function __construct($host,$user,$pass,$db)
{
	
	$this->user = $user;
	$this->pass = $pass;
	$this->host = $host;
	$this->db   = $db;
	
$conn = mysql_connect($this->host,$this->user,$this->pass);
return (mysql_select_db($db,$conn));
}

/**
 * Function query() - Executes a MySQL query
 *
 * Simply passes back the result from mysql_query into a MySQL
 * result resource variable
 *
 * @param string $sql - The SQL statements to be executed
 * @return MySQL result resource
*/ 
public function query($sql)
{  
   // echo $sql;
    return mysql_query($sql); }

/**
 * Function rows() - Returns the number of rows from a MySQL query
 *
 * Passed back the number of rows returned by mysql_query into a MySQL
 * result resource variable
 *
 * @param resource $conn
 * return integer
*/
public function rows($conn)
{ 
  if (@mysql_num_rows($conn) <= 0)
  {
   return 0;
  }
  else {
        return mysql_num_rows($conn); 
       }
}

/**
 * Function fetch() - Returns a record set as an array or object based on the result of a MySQL query
 *
 * This is a simple wrapper that combines mysql_fetch_array and mysql_fetch_object into one 
 * function call returning either an array or object determined by the second parameter passed 
 * to this function - and nothing more
 *
 * @param resource $conn - The database connection resource
 * @param string $rType  - 'array' or 'object' defaults to 'object'
 * return MySQL record set
*/
public function fetch($conn,$rType)
{ 
  if ($rType == 'array')
  {
    $rtn = mysql_fetch_array($conn);
  }
  else {
      
      if ($_GET['debug'] <= 0)
        $rtn =  @mysql_fetch_object($conn);
        else
                      $rtn =  mysql_fetch_object($conn);
       }
      // echo mysql_error() . $conn;
      // phpinfo();
       return $rtn;
}

/**
 * Function fields() - Returns an array or field (column) names from a MySQL query
 *
 * @param resource $conn
 * @return Array
 * @see fetch()
*/
public function fields($conn)
{ return mysql_fetch_fields($conn); }


public function dbErr($q)
{ return mysql_error(); }

public function dbErrno()
{ return mysql_errno(); }

}


?>
