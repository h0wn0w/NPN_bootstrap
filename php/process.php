<?php
//variables to be input
$name=$_POST['name']; 
$email=$_POST['email']; 
//connect to and select database
mysql_connect("localhost", "root", "h0wn0w") or die(mysql_error()); 
mysql_select_db("test") or die(mysql_error()); 
mysql_query("INSERT INTO `people` VALUES ('$name', '$email')"); 
Print "Your information has been successfully added to the database."; ?>
