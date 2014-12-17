<?php
require_once __DIR__ . "/../src/main/php/com/selfcoders/pini/Pini.php";

use com\selfcoders\pini\Pini;

$ini = new Pini(__DIR__ . "/example.ini");

$section = $ini->getSection("my section");

$property = $section->getProperty("some key");

echo $property->value;// This will return "some value"