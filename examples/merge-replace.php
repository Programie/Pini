<?php
require_once __DIR__ . "/../Ini.class.php";

$ini1 = new Ini(__DIR__ . "/example.ini");
$ini2 = new Ini(__DIR__ . "/example3.ini");

echo $ini1->getValue("my section", "some key") . "\n";// This will return "some value"

$ini1->merge($ini2);

echo $ini1->getValue("my section", "some key") . "\n";// This will return "replaced value"