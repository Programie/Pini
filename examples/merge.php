<?php
require_once __DIR__ . "/../Ini.class.php";

$ini1 = new Ini(__DIR__ . "/example.ini");
$ini2 = new Ini(__DIR__ . "/example2.ini");

$ini1->merge($ini2);

echo $ini1->getValue("another section", "some key");// This will return "another value" from the merged ini