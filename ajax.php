<?php

require_once 'classThermistor.php';

$Therm1 = new Thermistor();

echo $Therm1->getTemp();