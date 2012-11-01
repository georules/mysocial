<?php
include("database_queries.php");
if ($_GET["logout"] == "1")
{
        set_user_logged_out();
        $userID = 0;
}
else
{
        $userID = get_user_logged_in();
}
?>

<!-- This is the start of the MySocial HTML code for every page-->
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Social Networking Website</title>
</head>

<body>
<div id = "topcontainer">

<div id = "header">
<!-- NOTE: here you can modify the header text of this website.  Perhaps, add an image -->
<a href = "index.php">Social Networking Website</a>
</div>

<div id = "navigation">
<?php
        echo ("| <a href = 'index.php'> Home </a> | ");
        if ($userID != 0)
        {
		echo("<a href = 'user.php?userID=".$userID."'>Profile</a> | ");
        }
?>
</div>

<div id = "login">
<?php
        if ($userID == 0)
        {
                echo("| <a href = 'user_form.php'>Login or create new user</a> | ");
        }
        else
        {
                $username = database_get_username($userID);
                echo("| Welcome, " . $username . " | ");
                echo("<a href = 'index.php?logout=1'>Logout</a> |");
        }
?>
</div>

</div>
