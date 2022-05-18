<?php
require_once('../class/classSQLi.php');

$tempCON = new SQLi();
echo $tempCON->getLastEntries();
