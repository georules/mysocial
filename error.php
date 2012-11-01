<?php
include("top.php");

$errorcode = $_GET["code"];

// Error code 1 comes from newuser_process.php
// Error code 2 comes from user_process.php

if ($errorcode == "1")	{
	echo "<body><div id = 'container'>";

	echo "The user could not be created.  Please check the following:<br />";
?>
<ol><li>That this username has not already been taken</li>
<li>That you have created your "users" table correctly.  (userID, username, password, picture) fields have all been made and are the right CASE (e.g. ID is in caps for userID.)</li>
<li>That your MySQL query in the database_add_users function in database_queries.php does not have any errors.</li>
<li>Usernames and passwords selected cannot be blank.</li>
</ol><?php
	echo "<a href = 'user_form.php'>Please try again</a>";

	echo "</div></body></html>";
}

if ($errorcode == "2")	{

	echo "<body><div id = 'container'>";

        echo "Sorry, your username or password was incorrect.  Please check the following: <br />";
	?><ol><li>That you have provided a correct username/password</li>
<li>That your password field on the database is VARCHAR(100).  This field needs to be at least 100 long, not just 30, so that the entire password hash can be stored properly.  You will need to remove all users that you made previously if you did not have the password field set to VARCHAR(100). </li></ol><?php
        echo "<a href = 'user_form.php'>Please try again</a>";

	echo "</div></body></html>";
}
