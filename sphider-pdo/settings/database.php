<?php
	define('TABLE_PREFIX', "dang");		/* typically only needed for MySQL */
	define('DATABASE_NAME', 'sphider');	/* only needed for MySQL */
  $db = new PDO('mysql:host=localhost; dbname='.DATABASE_NAME, 'root', 'h0wn0w');	
?>
