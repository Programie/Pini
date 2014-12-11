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
		$ini2 = new Pini(__DIR__ . "/../examples/example3.ini");

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
		$ini2 = new Pini(__DIR__ . "/../examples/example3.ini");

		$ini->merge($ini2);

		$this->assertEquals(array("replaced"), $ini->getValue("arrays", "myarray"));
	}

	public function testSave()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$ini->setValue("some section", "some key", "some value");

		$ini->save();

		$ini2 = new Pini($filename);

		$this->assertEquals("some value", $ini2->getValue("some section", "some key"));

		unlink($filename);
	}

	public function testSaveArray()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$ini->setValue("some section", "some key", array("value 1", "value 2"));

		$ini->save();

		$ini2 = new Pini($filename);

		$this->assertEquals(array("value 1", "value 2"), $ini2->getValue("some section", "some key"));

		unlink($filename);
	}

	public function testMergeSave()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$ini->setValue("my section", "some key", "this value will be replaced");
		$ini->setValue("my section", "some other key", "this value will not be replaced");

		$ini->merge(new Pini(__DIR__ . "/../examples/example.ini"));

		$ini->save();

		$ini2 = new Pini($filename);

		$this->assertEquals("some value", $ini2->getValue("my section", "some key"));
		$this->assertEquals("this value will not be replaced", $ini2->getValue("my section", "some other key"));

		unlink($filename);
	}
}