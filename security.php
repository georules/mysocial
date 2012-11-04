<?php
function sanitize_input($s)
{
        $s = strip_tags($s);
        $s = mysql_real_escape_string($s);
        return $s;
}

// This function authorizes a username/password combination
function database_user_login($username, $password)
{
        $username = sanitize_input($username);
        $password = sanitize_input($password);
        $userID = database_get_userID($username);

	$q = "SELECT password FROM users WHERE userID='$userID'";
        $result = mysql_query($q);
        $row = mysql_fetch_array($result);
        $datapass = $row['password'];

        // If the database password and the passed in password are the same
        // the user is verified.  Otherwise, return 0.
        if (validate_password($password, $datapass))
        {
                set_user_logged_in($userID);
        }
        else
        {
                set_user_logged_out();
                $userID = 0;
        }

        return $userID;
}


function set_user_logged_out()
{
        session_destroy();
}

function get_user_logged_in()
{
	if (empty($_SESSION["user"]))	{
		return 0;
	}
	else	{
        	return $_SESSION["user"];
	}
}

function set_user_logged_in($userID)
{
	$_SESSION["user"] = $userID;
}

// Password hashing https://github.com/georules/simple-php-hashpass

/* Author: Geoffery L. Miller
Code below uses SHA256
http://php.net/manual/en/function.crypt.php

Using PBKDF2 would be better, but iSpace does not have mcrypt installed
http://www.php.net/manual/en/book.mcrypt.php
http://crackstation.net/hashing-security.htm
https://defuse.ca/php-pbkdf2.htm

Using Blowfish would be better, but iSpace has php 5.3.3 and 
$2y$ mode was added in php 5.3.7
http://phpmaster.com/why-you-should-use-bcrypt-to-hash-stored-passwords/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

Please see http://www.gnu.org/licenses/gpl.txt for GPL3 information
*/
function create_salt($saltlen=16) {
	$salt = ""; 
	$chars = "./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$numchars = strlen($chars)-1;
	for ($i = 0; $i < $saltlen; $i++) { 
		$rand = mt_rand(0, $numchars);
		$salt .= substr($chars, $rand, 1); 
	} 
	return $salt;
}
function create_hash($password, $hashsalt=null, $mode='$5$rounds=5000$') {
	if (empty($hashsalt))	{
		$salt = create_salt();
		$salty = $mode.$salt;
	}
	else	{
		$l = strlen($mode);
		$salty = substr($hashsalt,0,$l+16);
	}
	$hash = crypt($password,$salty);
	return $hash;
}
function validate_password($password, $hash)	{
	$hash_check = create_hash($password, $hash);
	if (slow_equals($hash,$hash_check))	{
		return true;
	}
	else	{
		return false;
	}
}

/* Author: havoc AT defuse.ca
	https://defuse.ca/php-pbkdf2.htm */
function slow_equals($a, $b)	{
	$diff = strlen($a) ^ strlen($b);
	for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)	{
		$diff |= ord($a[$i]) ^ ord($b[$i]);
	}
	return $diff === 0; 
}

?>
