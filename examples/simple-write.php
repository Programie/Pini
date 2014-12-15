<?php
require_once __DIR__ . "/../Pini.class.php";

$ini = new Pini();

$section = new PiniSection("my section");

$section->comment = array("A section can contain a comment", "even over multiple lines");

$ini->addSection($section);

$section->addProperty(new PiniProperty("some key", "the value to save"));

$property = new PiniProperty("integer value", "12345");
$property->comment = array("This is a comment for the integer value property");
$section->addProperty($property);

$ini->save(__DIR__ . "/example-write.ini");