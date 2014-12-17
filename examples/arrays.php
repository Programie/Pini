<?php
require_once __DIR__ . "/../src/main/php/com/selfcoders/pini/Pini.php";

use com\selfcoders\pini\Pini;

$ini = new Pini(__DIR__ . "/example.ini");

$section = $ini->getSection("arrays");

$property = $section->getProperty("myarray");

var_dump($property->value);// This will return an array containing 3 values