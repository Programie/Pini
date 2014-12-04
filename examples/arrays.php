<?php
require_once __DIR__ . "/../Ini.class.php";

$ini = new Ini(__DIR__ . "/example.ini");

var_dump($ini->getValue("arrays", "myarray"));// This will return an array containing 3 values