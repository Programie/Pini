<?php
require_once __DIR__ . "/../Pini.class.php";

$ini = new Pini(__DIR__ . "/example.ini");

$section = $ini->getSection("my section");

$property = $section->getProperty("some key");

echo $property->value;// This will return "some value"