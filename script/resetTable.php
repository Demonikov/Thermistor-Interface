<?php
require_once('../class/classSQLi.php');

$tempCON = new SQLi();
$tempCON->resetTable("temperature");
$tempCON->resetTable("adc");
$tempCON->resetTable("target");
