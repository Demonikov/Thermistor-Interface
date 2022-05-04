<?php

class SQLi
{

protected $con;

function __construct()
{
	$db = $_GET['DB'];
	$this->con = new mysqli("localhost", "phpmyadmin", "phpmyadmin", $db);

	if ($this->con->connect_error)
		die("Could not connect:" . $this->con->mysqli_error());
}

public function resetTable($table)
{
	$sql = "DELETE FROM $table";
	$this->con->query($sql);
	$sql = "ALTER TABLE $table AUTO_INCREMENT=1";
	$this->con->query($sql);
}

public function getLastEntries()
{
	if (filter_input(INPUT_GET, 'NUM', FILTER_VALIDATE_INT))
		$num = $_GET['NUM'];
	else
		$num = 1;


	$sql = "SELECT Temperature,Unit,Time FROM temperature ORDER BY ID DESC LIMIT $num";
	$result = $this->con->query($sql);
 
	// Transforme la réponse en JSON
	if ($result->num_rows > 0) {
		for ($i = 0; $i < $result->num_rows; $i++)
			$array[] = $result->fetch_assoc();
	
		echo json_encode($array);
	}else{
		return "No data";
	}
	
	return json_encode($array);
}

function __destruct()
{
	$this->con->close();
}
}
