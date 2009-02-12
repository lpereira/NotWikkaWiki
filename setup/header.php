<?php

// stuff
function test($text, $condition, $errorText = "", $stopOnError = 1) {
	print("$text ");
	if ($condition)
	{
		print("<span class=\"ok\">OK</span><br />\n");
	}
	else
	{
		print("<span class=\"failed\">FAILED</span>");
		if ($errorText) print(": ".$errorText);
		print("<br />\n");
		if ($stopOnError)
		{
			include('setup/footer.php');
			exit;
		}
	}
}

function myLocation()
{
	list($url, ) = explode("?", $_SERVER["REQUEST_URI"]);
	return $url;
}

/**
 * Delete a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.2
 * @param       string   $dirname    Directory to delete
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function rmdirr($dirname)
{
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }
 
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
 
    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Recurse
        rmdirr("$dirname/$entry");
    }
 
    // Clean up
    $dir->close();
    return rmdir($dirname);
}

function DeleteCookie($name) { SetCookie($name, "", 1, "/"); $_COOKIE[$name] = ""; }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Wikka Installation</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="keywords" content="Wikka Wakka Wiki" />
	<meta name="description" content="A WakkaWiki clone" />
	<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<style type="text/css">
/* Wikka Installer Stylesheet */
body {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: 13px;
	background-color: #EEE;	
	color: #000;
	margin: 0;
	padding: 0;
	line-height: 18px;
}

div.header {
	padding: 30px 15px 15px 15px;
	background-color: #FFF;	
	border-bottom: 1px solid #CCC;
}

div.page {
	padding: 15px;
}
a {
	color: #800;
}

input:hover {
	background-color: #F3F3F3;
}

input:focus {
	background-color: #E6E6E6;
}

input[type=submit], input[type=button] {
	font-family: Arial, Helvetica, sans-serif;
	padding: 2px 3px;
	font-size: 9pt;
	color:#666;
	font-weight: bold;
	background: #EEE;
	border-top: 1px solid #FFF;
	border-left: 1px solid #FFF;
	border-right: 1px solid #AAA;
	border-bottom: 1px solid #AAA;
}

input[type=submit]:hover, input[type=button]:hover {
	color: #333;
	background-color: #DDD;
	border-top: 1px solid #FFF;
	border-left: 1px solid #FFF;
	border-right: 1px solid #999;
	border-bottom: 1px solid #999;
}

input[type=password], input[type=text] {
	font-family: Arial, Helvetica, sans-serif;
	color: #333;
	font-weight: bold;
	font-size: 1em;
}

.ok {
	color: #080;
	font-weight: bold;
}

.failed {
	color: #800;
	font-weight: bold;
}


h2 {
	font-size: 10pt;
}

h1 {
	font-size: 13pt;
}

abbr {
	border-bottom: 1px dotted #333;
	cursor: help;
}

ul {
	margin: 2px;
	padding: 5px;
}

.note{
	display: block;
	background-color: #FEFEFE;
	border: 1px solid #CCC;
	margin: 5px 0px;
	padding: 5px;
	font-size: 12px;
	line-height: 18px;
}

xmp{
	display: block;
	border: 1px solid #CCC;
	background-color: #FEFEFE;
	padding: 10px;
}

/* inline system messages */
em.error {
	color: #A33; 
	font-style: normal;
	font-weight: bold;
	font-size: 95%;
}

em.success {
	color: #3A3; 
	font-style: normal;
	font-weight: bold;
	font-size: 95%;
}
</style>	
</head>
<body>
<div class="header">
	<img src="images/wikka_logo.jpg" alt="wikka logo" title="Welcome to Wikka" />
</div>
<div class="page"><!--START page body -->