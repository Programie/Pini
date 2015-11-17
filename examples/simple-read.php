<?php
require_once __DIR__ . "/../src/main/php/com/selfcoders/pini/Pini.php";

use com\selfcoders\pini\Pini;

$ini = new Pini(__DIR__ . "/example.ini");

$section = $ini->getSection("my section");

$property = $section->getProperty("some key");

echo $property->value;// This will return "some value"

echo $section->getPropertyValue("some key");// This will also return "some value"

echo $section->getPropertyValue("not existing key", "fallback value");// This will return "fallback value" as the key does not exist