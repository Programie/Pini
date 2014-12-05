<?php
require_once __DIR__ . "/../Ini.class.php";

class IniTest extends PHPUnit_Framework_TestCase
{
	public function testReadValue()
	{
		$ini = new Ini(__DIR__ . "/../examples/example.ini");

		$this->assertEquals("some value", $ini->getValue("my section", "some key"));
	}

	public function testMerge()
	{
		$ini = new Ini(__DIR__ . "/../examples/example.ini");
		$ini2 = new Ini(__DIR__ . "/../examples/example3.ini");

		$ini->merge($ini2);

		$this->assertEquals("replaced value", $ini->getValue("my section", "some key"));
	}

	public function testArray()
	{
		$ini = new Ini(__DIR__ . "/../examples/example.ini");

		$this->assertEquals(array("value", "other value", "even another value"), $ini->getValue("arrays", "myarray"));
	}

	public function testMergeArray()
	{
		$ini = new Ini(__DIR__ . "/../examples/example.ini");
		$ini2 = new Ini(__DIR__ . "/../examples/example3.ini");

		$ini->merge($ini2);

		$this->assertEquals(array("replaced"), $ini->getValue("arrays", "myarray"));
	}

	public function testSave()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Ini($filename);

		$ini->setValue("some section", "some key", "some value");

		$ini->save();

		$ini2 = new Ini($filename);

		$this->assertEquals("some value", $ini2->getValue("some section", "some key"));

		unlink($filename);
	}

	public function testSaveArray()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Ini($filename);

		$ini->setValue("some section", "some key", array("value 1", "value 2"));

		$ini->save();

		$ini2 = new Ini($filename);

		$this->assertEquals(array("value 1", "value 2"), $ini2->getValue("some section", "some key"));

		unlink($filename);
	}
}