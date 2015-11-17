<?php
use com\selfcoders\pini\Pini;

require_once __DIR__ . "/../vendor/autoload.php";

$ini = new Pini(__DIR__ . "/example.ini");

$section = $ini->getSection("my section");// Returns a PiniSection instance if the section was found, otherwise returns null

echo "Section: " . $section->name . "\n";
echo "Comment: " . implode("\n", $section->comment) . "\n";

echo "\n";

$property = $section->getProperty("some key");// Returns a PiniProperty instance if the property was found, otherwise returns null

echo "Key: " . $property->name . "\n";
echo "Comment: " . implode("\n", $property->comment) . "\n";
echo "Value: " . $property->value . "\n";

echo "\n";

$property = $section->getProperty("another key");// Returns a PiniProperty instance if the property was found, otherwise returns null

echo "Key: " . $property->name . "\n";
echo "Comment: " . implode("\n", $property->comment) . "\n";
echo "Value: " . $property->value . "\n";