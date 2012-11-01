<?php
include("database_queries.php");

// Get the username and password from the form
$username = $_POST["username"];
$password = $_POST["password"];

// Log the user in
$userID = database_user_login($username, $password);

if ($userID == 0)
{
	// This user could not login, send to an error page
	header('Location: error.php?code=2');
}
else
{
	// Go to the new user's page
	header('Location: user.php?userID=' . $userID);
}
?>

