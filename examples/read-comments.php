<?php
require_once __DIR__ . "/../Pini.class.php";

$ini = new Pini(__DIR__ . "/commented.ini");

$property1 = $ini->getProperty("my section", "my key");
$property2 = $ini->getProperty("my section", "another key");

echo implode("\n", $property1->comment);// This will return "And here you can see an example property"
echo "\n";
echo implode("\n", $property2->comment);// This will return "You may even use\nmultiple lines\nin comments" (\n replaced by new line)