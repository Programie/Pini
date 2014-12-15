<?php
require_once __DIR__ . "/../Pini.class.php";

class PiniTest extends PHPUnit_Framework_TestCase
{
	public function testReadValue()
	{
		$ini = new Pini(__DIR__ . "/../examples/example.ini");

		$this->assertEquals("some value", $ini->getValue("my section", "some key"));
	}

	public function testMerge()
	{
		$ini = new Pini(__DIR__ . "/../examples/example.ini");
		$ini2 = new Pini(__DIR__ . "/../examples/example-merge.ini");

		$ini->merge($ini2);

		$this->assertEquals("replaced value", $ini->getValue("my section", "some key"));
	}

	public function testArray()
	{
		$ini = new Pini(__DIR__ . "/../examples/example.ini");

		$this->assertEquals(array("value", "other value", "even another value"), $ini->getValue("arrays", "myarray"));
	}

	public function testMergeArray()
	{
		$ini = new Pini(__DIR__ . "/../examples/example.ini");
		$ini2 = new Pini(__DIR__ . "/../examples/example-merge.ini");

		$ini->merge($ini2);

		$property = $ini->getProperty("arrays", "myarray");

		$this->assertEquals(array("replaced"), $property->value);
	}

	public function testSave()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$property = new PiniProperty("some key", "some value");
		$ini->setProperty("some section", $property);

		$ini->save();

		$ini2 = new Pini($filename);

		$property2 = $ini2->getProperty("some section", "some key");

		$this->assertEquals("some value", $property2->value);

		unlink($filename);
	}

	public function testSaveArray()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$property = new PiniProperty("some key", array("value 1", "value 2"));
		$ini->setProperty("some section", $property);

		$ini->save();

		$ini2 = new Pini($filename);

		$property2 = $ini2->getProperty("some section", "some key");

		$this->assertEquals(array("value 1", "value 2"), $property2->value);

		unlink($filename);
	}

	public function testMergeSave()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$property1 = new PiniProperty("some key", "This value will be replaced");
		$property2 = new PiniProperty("some other key", "This value will not be replaced");

		$ini->setProperty("some section", $property1);
		$ini->setProperty("some section", $property2);

		$ini2 = new Pini();

		$property3 = new PiniProperty("some key", "Replaced value");
		$property4 = new PiniProperty("some additional key", "New value");

		$ini2->setProperty("some section", $property3);
		$ini2->setProperty("some section", $property4);

		$ini->merge($ini2);

		$ini->save();

		$ini3 = new Pini($filename);

		$property5 = $ini3->getProperty("some section", "some key");
		$property6 = $ini3->getProperty("some section", "some other key");
		$property7 = $ini3->getProperty("some section", "some additional key");

		$this->assertEquals("Replaced value", $property5->value);
		$this->assertEquals("This value will not be replaced", $property6->value);
		$this->assertEquals("New value", $property7->value);

		unlink($filename);
	}
}