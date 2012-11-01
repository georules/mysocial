<?php
require_once("connect.php");
$id = $_GET['id'];
$id = mysql_real_escape_string(strip_tags($id));
$q = "SELECT picture FROM users WHERE userID = '$id'";
$r = mysql_query($q);
$row = mysql_fetch_array($r);
header("Content-type: image");
echo $row['picture'];
mysql_close();
?>
