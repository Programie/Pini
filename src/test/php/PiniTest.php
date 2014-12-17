<?php
use com\selfcoders\pini\Pini;
use com\selfcoders\pini\Property;
use com\selfcoders\pini\Section;

class PiniTest extends PHPUnit_Framework_TestCase
{
	public function testReadValue()
	{
		$ini = new Pini(__DIR__ . "/../../../examples/example.ini");

		$section = $ini->getSection("my section");

		$property = $section->getProperty("some key");

		$this->assertEquals("some value", $property->value);
	}

	public function testMerge()
	{
		$ini = new Pini(__DIR__ . "/../../../examples/example.ini");
		$ini2 = new Pini(__DIR__ . "/../../../examples/example-merge.ini");

		$ini->merge($ini2);

		$section = $ini->getSection("my section");

		$property = $section->getProperty("some key");

		$this->assertEquals("replaced value", $property->value);
	}

	public function testArray()
	{
		$ini = new Pini(__DIR__ . "/../../../examples/example.ini");

		$section = $ini->getSection("arrays");

		$property = $section->getProperty("myarray");

		$this->assertEquals(array("value", "other value", "even another value"), $property->value);
	}

	public function testMergeArray()
	{
		$ini = new Pini(__DIR__ . "/../../../examples/example.ini");
		$ini2 = new Pini(__DIR__ . "/../../../examples/example-merge.ini");

		$ini->merge($ini2);

		$section = $ini->getSection("arrays");

		$property = $section->getProperty("myarray");

		$this->assertEquals(array("replaced"), $property->value);
	}

	public function testSave()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$property = new Property("some key", "some value");

		$section = new Section("some section");
		$section->addProperty($property);

		$ini->addSection($section);

		$ini->save();

		$ini2 = new Pini($filename);

		$section2 = $ini2->getSection("some section");

		$property2 = $section2->getProperty("some key");

		$this->assertEquals("some value", $property2->value);

		unlink($filename);
	}

	public function testSaveArray()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$property = new Property("some key", array("value 1", "value 2"));

		$section = new Section("some section");
		$section->addProperty($property);

		$ini->addSection($section);

		$ini->save();

		$ini2 = new Pini($filename);

		$section2 = $ini2->getSection("some section");

		$property2 = $section2->getProperty("some key");

		$this->assertEquals(array("value 1", "value 2"), $property2->value);

		unlink($filename);
	}

	public function testMergeSave()
	{
		$filename = tempnam(sys_get_temp_dir(), "ini");

		$ini = new Pini($filename);

		$section = new Section("some section");

		$section->addProperty(new Property("some key", "This value will be replaced"));
		$section->addProperty(new Property("some other key", "This value will not be replaced"));

		$ini->addSection($section);

		$ini2 = new Pini();

		$section2 = new Section("some section");

		$section2->addProperty(new Property("some key", "Replaced value"));
		$section2->addProperty(new Property("some additional key", "New value"));

		$ini2->addSection($section2);

		$ini->merge($ini2);

		$ini->save();

		$ini3 = new Pini($filename);

		$section3 = $ini3->getSection("some section");

		$property1 = $section3->getProperty("some key");
		$property2 = $section3->getProperty("some other key");
		$property3 = $section3->getProperty("some additional key");

		$this->assertEquals("Replaced value", $property1->value);
		$this->assertEquals("This value will not be replaced", $property2->value);
		$this->assertEquals("New value", $property3->value);

		unlink($filename);
	}
}