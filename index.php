<?php include("top.php");
// This include provides $userID as the user logged in.
?>

<div id = "container">
<div id = "header"><strong>Home</strong></div>

<div id = "friend_post_container">
<?php
// Show Friend Statuses
// 0 means that there is not a logged in user
if ($userID > 0)	{
	// If there is a logged in user, show status updates from your friends
	echo("<h2>News Feed</h2>");
	echo("<div id = 'posts'>");
	database_show_friend_posts($userID);
	echo("</div>");
}
?>
</div>

<div id = "user_container">
<h2>Members</h2>
<?php
// Show all of the users on the website
database_show_users();
?>
</div>

</div>
</body>
</html>

