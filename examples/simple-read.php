<?php
require_once __DIR__ . "/../Pini.class.php";

$ini = new Pini(__DIR__ . "/example.ini");

echo $ini->getValue("my section", "some key");// This will return "some value"