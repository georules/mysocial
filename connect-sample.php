<?php
// Connect to the database

// Database connection information
// NOTE: Modify these variables to include your information
$server="DATABASE_SERVER";
$mysql_username="DATABASE_USER";
$mysql_password="DATABASE_PASS";
$database="DATABASE_NAME";

// Database Connection Commands
if (!($db = mysql_connect($server, $mysql_username , $mysql_password)))
{
	// If the connect function did not work
	die("Error: can't connect to database server. Check your connect.php file.");}
else
{
	// The connection to the server did work, select a database
	if (!(mysql_select_db("$database",$db))){
		// If the database selection did not work
     		die("Can't connect to database.  Check your connect.php file.");
	}
}
// This starts or resumes a session with the client login
session_start();
?>
