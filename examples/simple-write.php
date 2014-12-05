<?php
require_once __DIR__ . "/../Ini.class.php";

$ini = new Ini();

$ini->setValue("my section", "some key", "the value to save");

$ini->setValue("my section", "integer value", 12345);

$ini->save(__DIR__ . "/example-write.ini");