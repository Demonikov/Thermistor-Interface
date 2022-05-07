<?php

class SQLi
{

protected $con;
protected $table;

function __construct()
{
	$db = $_GET['DB'];
	$this->table = $_GET['TABLE'];

	console.log($this->table);
	$this->con = new mysqli("localhost", "phpmyadmin", "phpmyadmin", $db);

	if ($this->con->connect_error)
		die("Could not connect:" . $this->con->mysqli_error());
}

// Erase all table entries
public function resetTable()
{
	$this->con->query("DELETE FROM $this->table");
	$this->con->query("ALTER TABLE $this->table AUTO_INCREMENT=1");
}

// Send 
public function sqlQuery($query)
{
	$result = $this->con->query($query);
	
	// Transforme la réponse en JSON
	if ($result->num_rows > 0) {
		for ($i = 0; $i < $result->num_rows; $i++)
			$array[] = $result->fetch_assoc();
	}else{
		return "No data";
	}
	
	return json_encode($array);
}

public function getLastEntries()
{
	if (filter_input(INPUT_GET, 'NUM', FILTER_VALIDATE_INT))
		$num = $_GET['NUM'];
	else
		$num = 1;

	return $this->sqlQuery("SELECT * FROM $this->table ORDER BY ID DESC LIMIT $num");
 }

function __destruct()
{
	$this->con->close();
}
}
