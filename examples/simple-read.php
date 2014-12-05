<?php
require_once __DIR__ . "/../Ini.class.php";

$ini = new Ini(__DIR__ . "/example.ini");

echo $ini->getValue("my section", "some key");// This will return "some value"