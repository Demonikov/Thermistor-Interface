<?php
$db = $_GET['DB'];

$sql_con = mysqli_connect("localhost", "phpmyadmin", "phpmyadmin", $db);
if (!$sql_con)
	die("Could not connect:" . mysqli_error());

$sql = "DELETE FROM temperature";
mysqli_query($sql_con, $sql);
$sql = "ALTER TABLE temperature AUTO_INCREMENT=1";
$result = mysqli_query($sql_con, $sql);

mysqli_close($sql_con);
