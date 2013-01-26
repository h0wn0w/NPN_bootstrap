<?php 
 // Connects to your Database 
mysql_connect("localhost", "root", "h0wn0w") or die(mysql_error()); 
mysql_select_db("test") or die(mysql_error());
$data = mysql_query("SELECT * FROM people") 
or die(mysql_error()); 
Print "<table border cellpadding=3>"; 
while($info = mysql_fetch_array( $data )) 
{ 
Print "<tr>"; 
Print "<th>Name:</th> <td>".$info['name'] . "</td> "; 
Print "<th>Email:</th> <td>".$info['email'] . " </td></tr>"; 
} 
Print "</table>"; 
?>