<?php
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
}