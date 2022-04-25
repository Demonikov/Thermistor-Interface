<?php
$db = $_GET['DB'];
$con = new mysqli("localhost", "phpmyadmin", "phpmyadmin", $db);

if ($con->connect_error)
	die("Could not connect:" . $con->mysqli_error());

$sql = "SELECT Temperature,Unit,Time FROM temperature ORDER BY ID DESC LIMIT 1";
$result = $con->query($sql);

if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		echo json_encode( array("Temperature" => $row["Temperature"], "Unit" => $row["Unit"], "Time" => $row["Time"]) );
	}
}else{
	echo "No data";
}

$con->close();
