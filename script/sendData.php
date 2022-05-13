<?php
require_once('../class/classThermistor.php');
require_once('../class/classSQLi.php');

$SQL_CON = new SQLi();
$Therm1 = new Thermistor();

if ($_GET["TARGET_STATE"])
	shell_exec("systemctl start Thermostat");
else
	shell_exec("systemctl stop Thermostat");

// Read shield ADC and calculate temperature
$tmp = json_decode(shell_exec("./shieldpic16f88"), true);
$Therm1->setVadc($tmp["Vadc"]);

// Send temperature to DB
$realTemperature = json_decode( $Therm1->getTemp(), true );
$SQL_CON->sqlQuery("INSERT INTO `adc` (`Temperature`) VALUES ($realTemperature[Temperature])");

// Send target temperature to DB
if (filter_input(INPUT_GET, 'TARGET', FILTER_VALIDATE_FLOAT))
	$SQL_CON->sqlQuery("INSERT INTO `target` (`Target`) VALUES ($_GET[TARGET])");
