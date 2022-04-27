<?php
/*
 * Retourne les dernières mesures de température sur le serveur SQL
 * */


$db = $_GET['DB'];

// Nombre de ligne à demander au serveur SQL
if (filter_input(INPUT_GET, 'NUM', FILTER_VALIDATE_INT))
	$num = $_GET['NUM'];
else
	$num = 1;

// Connection au serveur
$con = new mysqli("localhost", "phpmyadmin", "phpmyadmin", $db);

if ($con->connect_error)
	die("Could not connect:" . $con->mysqli_error());

// Requete
$sql = "SELECT Temperature,Unit,Time FROM temperature ORDER BY ID DESC LIMIT $num";
$result = $con->query($sql);

// Transforme la réponse en JSON
if ($result->num_rows > 0) {
	for ($i = 0; $i < $result->num_rows; $i++)
		$array[] = $result->fetch_assoc();
	
	echo json_encode($array);
}else{
	echo "No data";
}

$con->close();
