<?php

require_once 'classThermistor.php';

$Therm1 = new Thermistor();
$Therm1->sendToDB();
echo $Therm1->getTemp();
