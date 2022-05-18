<?php

// Wait for SQL connection
do {
	$SQL_CON = new mysqli("localhost", "phpmyadmin", "phpmyadmin", "schema");
	//if ($SQL_CON->connect_errno)
	//	die("Could not connect:" . $SQL_CON->mysqli_error());
} while (! ($SQL_CON->ping()) );
	
// Get last row of specified table
function getLastRow($con, $table)
{
	$result = $con->query("SELECT * FROM $table ORDER BY ID DESC LIMIT 1");

	// Transforme la réponse en JSON
	if ($result->num_rows > 0)
		return $result->fetch_assoc();
	else
		return "No data";
}

shell_exec("raspi-gpio set 17 op");

while (1){
	$objTarget = getLastRow($SQL_CON, "target");
	$objTemp = getLastRow($SQL_CON, "adc");

	if ( floatval( $objTarget["Target"] ) >= floatval( $objTemp["Temperature"] ) )
		shell_exec("raspi-gpio set 17 dh");
	else
		shell_exec("raspi-gpio set 17 dl");
	sleep(0.5);
}

$SQL_CON->close();
