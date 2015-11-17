<?php
use com\selfcoders\pini\Pini;
use com\selfcoders\pini\Property;
use com\selfcoders\pini\Section;

require_once __DIR__ . "/../vendor/autoload.php";

$ini = new Pini();

$section = new Section("my section");

$section->comment = array("A section can contain a comment", "even over multiple lines");

$ini->addSection($section);

$section->addProperty(new Property("some key", "the value to save"));

$property = new Property("integer value", "12345");
$property->comment = array("This is a comment for the integer value property");
$section->addProperty($property);

$ini->save(__DIR__ . "/example-write.ini");