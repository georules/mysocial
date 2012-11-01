<?php
include("database_queries.php");

// Get the message to post
$post = $_POST["post"];

// Get the user logged in
$userID = get_user_logged_in();

// Insert the new post into the database for that user
database_add_user_post($userID, $post);

// Go back to the user's page
header('Location: user.php?userID=' . $userID);
?>

