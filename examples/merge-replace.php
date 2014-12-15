<?php
require_once __DIR__ . "/../Pini.class.php";

$ini1 = new Pini(__DIR__ . "/example.ini");
$ini2 = new Pini(__DIR__ . "/example-merge.ini");

$section = $ini1->getSection("my section");

$property = $section->getProperty("some key");

echo $property->value . "\n";// This will return "some value"

$ini1->merge($ini2);

echo $property->value . "\n";// This will still return "some value" as the instance of the old property won't be touched, instead the new property instance of the second ini will be added

$section = $ini1->getSection("my section");

$property = $section->getProperty("some key");

echo $property->value . "\n";// This will return "replaced value"