<?php
require_once 'class.dbh.php';
require_once 'class.form.php';   
require_once 'class.table.php';   


// Debug - set to 0 in production
if (!defined(DEBUG))
define(DEBUG,1);


// Database settings 
if (!defined(DBHOST))
define(DBHOST,'localhost');

if (!defined(DBUSER))
define(DBUSER,'lq');

if (!defined(DBPASS))
define(DBPASS,'********');

if (!defined(DBNAME))
define(DBNAME,'lq');

// Global site name
if (!defined(SITENAME))
define(SITENAME,'Library Queries Database');

// Don't touch it will thow the JQuery out of Sunc
if (!defined(SPLITBY))
define(SPLITBY,'~~~~');

if (!defined(DEFAULT_COUNTRY))
define(DEFAULT_COUNTRY,'IE');


// To use javascript or CSS simply add the file the correct array below - it must exist though
// Register Stylesheets
if (!defined(STYLES))
{
$styles = array('header.css','left.css','mainstylesheet.css','right.css','footer.css','calendar.css','skins/skyblue.css','dropdown.css');
define(STYLES,1);
}

// Register JavaScript
if (!defined(JAVASCRIPT))
{
$javascript = array('layout.js','validation.js','ajax-dynamic-list.js','ajax.js','calendar.js','dropdown.js');
define(JAVASCRIPT,1);
}
$dbh = new DBH(DBHOST,DBUSER,DBPASS,'lq');


// Seed for generation the randon MD5 hash for password encryption
define(SEED,"31415927");  

// USER Permissions   - DO NOT CHANGE
define(AUTHLOCALALADMIN,1);    // Local Library administrator
define(AUTHUSER,2);            // Normal user with restricted permissions
define(AUTHGLOBALADMIN,3);     // Global site wide consortium administrator


// SMTP SETTINGS - **** NOTE:
// This is set up for googele apps (gmail) 
// To use less secure methods such as MS Exchange, Sendmail, QMAIL e.t.c you'll need to change
// the post, TCL/SSL settings below. Google apps is the best method to use, others may result in the mail 
// being delivered to the recipients spam folder


  define(SMTPDEBUG,1);                                                      // enables SMTP debug information (for testing)
  define(SMTPAUTH,true);                                                    // enable SMTP authentication
  define(SMTPSECURE,"ssl");                                                 // sets the prefix to the servier
  define(MAILHOST,"smtp.gmail.com");                                        // sets GMAIL as the SMTP server
  define(MAILPORT,465);                                                     // set the SMTP port for the GMAIL server
  define(MAILUSER,"clients@forgeserversolutions.com");                      // GMAIL username
  define(MAILPASSWORD,"ulysses99");                                         // GMAIL password
  define(REPLYTOADDR,"<clients@forgeserversolutions.com>");                 // Replyto address 
  define(REPLYTONAME,"Library Queries Database");                           // Reply to friendly name 

function debug($line)
{
	if (DEBUG)
     {
	    $fh = fopen("./debug.log", "a+");
	    if (mysql_error() == "")
	       $err = " No errors ";
	            else $err = "Error: " . mysql_error();
	 
	   fwrite($fh,date("D M j G:i:s T Y") ." : " . $line . "\r\n" . $err . "\r\n ********\r\n");
     }
}
?>
