<?php
use com\selfcoders\pini\Pini;

require_once __DIR__ . "/../vendor/autoload.php";

$ini1 = new Pini(__DIR__ . "/example.ini");
$ini2 = new Pini(__DIR__ . "/example-merge.ini");

$ini1->merge($ini2);

$section = $ini1->getSection("another section");

$property = $section->getProperty("some key");

echo $property->value;// This will return "another value" from the merged ini