<?php
require_once('../class/classThermistor.php');
require_once('../class/classSQLi.php');

$SQL_CON = new SQLi();

$Therm1 = new Thermistor();

$tmp = json_decode(shell_exec("./shieldpic16f88"), true);
$Therm1->setVadc($tmp["Vadc"]);

$tmp = json_decode( $Therm1->getTemp(), true );
$realTemperature = $tmp["Temperature"];

$SQL_CON->sqlQuery("INSERT INTO `adc` (`Temperature`) VALUES ($realTemperature)");
