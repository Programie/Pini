<?php
use com\selfcoders\pini\Pini;

require_once __DIR__ . "/../vendor/autoload.php";

$ini = new Pini(__DIR__ . "/example.ini");

$section = $ini->getSection("my section");

echo "some key = " . var_export($property = $section->getProperty("some key")->boolean(), true) . "\n";
echo "some bool = " . var_export($property = $section->getProperty("some bool")->boolean(), true) . "\n";
echo "another bool = " . var_export($property = $section->getProperty("another bool")->boolean(), true) . "\n";