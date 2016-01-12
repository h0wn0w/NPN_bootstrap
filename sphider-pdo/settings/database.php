<?php
	define('TABLE_PREFIX', "PREFIX");		/* typically only needed for MySQL */
	define('DATABASE_NAME', 'DBNAME');	/* only needed for MySQL */
  $db = new PDO('mysql:host=localhost; dbname='.DATABASE_NAME, 'USER', 'PW');	
?>
