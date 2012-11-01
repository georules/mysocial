<?php
include("connect.php");
include("security.php");

// NOTE: You can write a new function like this function 
// to get other fields from the database for this userID
function database_get_username($userID)
{
        $userID = sanitize_input($userID);
        $data = mysql_query("SELECT username FROM users WHERE userID='$userID'");
        $row = mysql_fetch_array($data);
        $result = $row[0];
        return $result;
}

// NOTE: You will want to change the function name from "database_get_username"
// to something like "database_get_newfield" where newfield is the name of your new field.
// You will need to change the SQL query so that it selects newfield.

// NOTE: In this function, you can add more fields to be inserted into the database
// For example: database_add_user($username, $password, $picture, $newfield)
function database_add_user($username, $password, $picture)	// Add more variables here
{
        // Sanitize the variables you passed in
        $username = sanitize_input($username);
        $password = sanitize_input($password);
	// NOTE: Add another variable to be sanitized here:

	// Hash the password so that it is not stored in the database as plain text
	$password = create_hash($password);
	// Process the picture for putting it in the database
	$picture = process_picture($picture);

	// NOTE: modify this query to also include the newfield
	// Insert the new user into the database
	$q = "INSERT INTO users (username, password, picture) VALUES ('$username','$password','$picture')";

        // Check to see that name is not taken
	$check = check_username($username);
	$userID = 0;
        if ($check == 0)	{
                // Add the user to the database
		mysql_query($q);
                // Set this userID as logged in
                $userID = mysql_insert_id();
                set_user_logged_in($userID, $password);
        }
        return $userID;
}

//
// Below there are many ore database functions; you can study these more outside of the assignment
//

// This adds a message into the posts table
function database_add_user_post($userID, $message)
{
	$message = sanitize_input($message);
	mysql_query("INSERT INTO posts (userID, message) VALUES ('$userID', '$message')");
}

// This function opens the picture that was uploaded and prepares it for the database
function process_picture($pic)	{
	$data = "";
	if(filesize($pic) > 0)	{
		$data = imagecreatefromstring(file_get_contents($pic));
	}
	else	{
		// There was not a picture uploaded, use a default picture.
		$default_image = "images/default.png";
		$data = imagecreatefromstring(file_get_contents($default_image));
	}
	$newdata = resize_image($data,150,150);
	ob_start();
	imagejpeg($newdata);
	$contents = ob_get_contents();
	ob_end_clean();
	$newdata = addslashes($contents);
	return $newdata;
}

// This function resizes images and crops them down
function resize_image($data,$width,$height) {
	$w = imagesx($data);
	$h = imagesy($data);
	$ratio = min($width/$w, $height/$h);
	$width = $w * $ratio; $height = $h * $ratio;
	$newimg = imagecreatetruecolor($width,$height);
	imagecopyresampled($newimg, $data, 0,0,0,0,$width,$height,$w,$h);
	return $newimg;
}

// This function checks to see if a username is already in the users table
function check_username($username)	{
        // Check to see that name is not taken
        $result = mysql_query("SELECT count(*) FROM users WHERE username='" . $username . "'");
        $row = mysql_fetch_array($result);
        $num_users = $row[0];
	return $num_users;
}

// This function will mutually and automatically friend two people
function add_friend($userID, $friendID)
{
        $userID = sanitize_input($userID);
        $friendID = sanitize_input($friendID);

        mysql_query("INSERT INTO friends (userID, friendID) VALUES ('$userID', '$friendID')");
        mysql_query("INSERT INTO friends (userID, friendID) VALUES ('$friendID', '$userID')");
}

// This gets all of the friendIDs for a userID
function database_get_friends($userID)
{
        // Get all of the friends for this user
        $friends = array();
        $result = mysql_query("SELECT DISTINCT friendID FROM friends WHERE userID = $userID");
        $i = 0;
        while ($row = mysql_fetch_array($result))       {
                $friends[$i] = $row['friendID'];
                $i = $i + 1;
        }
	return $friends;
}

function database_show_friend_posts($userID)
{
	// Print out the most recent post from each friend userID have

	// Get all of the friends for this user
	$friends = database_get_friends($userID);
	$friends[sizeof($friends) + 1] = $userID;
	$s = "";
	// Print the most recent post from each friend
	for ($i = 0; $i <= sizeof($friends); $i++)	{
		$uID = $friends[$i];
		$q = "SELECT users.username, posts.message, posts.timestamp FROM users, posts WHERE posts.userID='$uID' AND users.userID='$uID' ";
		$q = $q . "ORDER BY posts.timestamp DESC LIMIT 2";

		$result = mysql_query($q);
		while($row = mysql_fetch_row($result))	{
			if ($row[0] != "")
				$s = $s . "<a href='user.php?userID=$uID'>" . $row[0] . "</a> ". $row[2]. ": " . $row[1] . "<br />";
		}
	}
	echo $s;
}

// This shows all of the users in a bunch of floating divs
function database_show_users()
{
        $result = mysql_query("SELECT userID, username, picture FROM users ORDER BY userID");
        while($row = mysql_fetch_array($result))
        {
		$userID = $row[0];
		$username = $row[1];
		$picture = $row[2];

		echo "<div id = 'user'>\n";
                echo "<a href = 'user.php?userID=" . $userID . "'>\n";
		echo "<img width = '75' height = '75' src = 'show_picture?id=$userID'><br />\n";
		echo $username;
		echo "</a></div>\n";
        }

}

// Get the userID for a particular username
function database_get_userID($username)
{
	$username = sanitize_input($username);
        $result = mysql_query("SELECT userID FROM users WHERE username = '" . $username . "'");
        $row = mysql_fetch_array($result);
        $userID = $row[0];

        return $userID;
}

// Get all of the posts for a userID
function database_get_user_posts($userID)
{
	$userID = sanitize_input($userID);
	$posts = "";

	// Get the database information
	$result = mysql_query("SELECT message,timestamp FROM posts WHERE userID='" . $userID . "' ORDER BY timestamp DESC");

	while($row = mysql_fetch_array($result))
	{
		$message = stripslashes($row[message]);
		$posts = $posts . $row[timestamp] . ": " . $message . "<br />";
	}

        return $posts;
}

?>
