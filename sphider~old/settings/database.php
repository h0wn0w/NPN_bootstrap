<?php
	$database="npnlook";
	$mysql_user = "npnow";
	$mysql_password = "park47&trunk"; 
>>>>>>> edits to important files including robots, gitignore, and settings database admin auth:sphider/settings/database.php
	$mysql_host = "serchin.db";
	$mysql_table_prefix = "thx";



	$success = mysql_pconnect ($mysql_host, $mysql_user, $mysql_password);
	if (!$success)
		die ("<b>Cannot connect to database, check if username, password and host are correct.</b>");
    $success = mysql_select_db ($database);
	if (!$success) {
		print "<b>Cannot choose database, check if database name is correct.";
		die();
	}
?>

