<?php
use com\selfcoders\pini\Pini;

require_once __DIR__ . "/../vendor/autoload.php";

$ini = new Pini(__DIR__ . "/example.ini");

$section = $ini->getSection("arrays");

$property = $section->getProperty("myarray");

var_dump($property->value);// This will return an array containing 3 values