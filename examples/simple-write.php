<?php
require_once __DIR__ . "/../Pini.class.php";

$ini = new Pini();

$ini->setValue("my section", "some key", "the value to save");

$ini->setValue("my section", "integer value", 12345);

$ini->save(__DIR__ . "/example-write.ini");