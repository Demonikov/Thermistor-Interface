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

public function getLastEntries($num)
{
	//$sql = "SELECT Temperature,Unit,Time FROM temperature ORDER BY ID DESC LIMIT $num";
	//return $con->query($sql);
}

function __destruct()
{
	$this->con->close();
}
}
