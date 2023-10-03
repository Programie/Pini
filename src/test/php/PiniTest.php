<?php
use com\selfcoders\pini\Pini;
use com\selfcoders\pini\Property;
use com\selfcoders\pini\Section;
use PHPUnit\Framework\TestCase;

class PiniTest extends TestCase
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

    public function testBoolean()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $section = $ini->getSection("my section");

        $this->assertNull($section->getProperty("some key")->boolean());
        $this->assertTrue($section->getProperty("some bool")->boolean());
        $this->assertFalse($section->getProperty("another bool")->boolean());
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

    public function testSaveDefaultSection()
    {
        $filename = tempnam(sys_get_temp_dir(), "ini");

        $ini = new Pini($filename);

        $section = new Section("some section");
        $section->addProperty(new Property("some key", "some value"));

        $ini->addSection($section);

        $ini->getDefaultSection()->addProperty(new Property("some key", "some value in default section"));

        $ini->save();

        $ini2 = new Pini($filename);

        $this->assertEquals("some value", $ini2->getSection("some section")->getPropertyValue("some key"));
        $this->assertEquals("some value in default section", $ini2->getDefaultSection()->getPropertyValue("some key"));

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

        $section->comment = array("A section comment");

        $property1 = new Property("some key", "This value will be replaced");
        $property1->comment = array("A property comment");
        $section->addProperty($property1);

        $property2 = new Property("some other key", "This value will not be replaced");
        $property2->comment = array("Another property comment");
        $section->addProperty($property2);

        $ini->addSection($section);

        $ini2 = new Pini();

        $section2 = new Section("some section");

        $section2->addProperty(new Property("some key", "Replaced value"));

        $property3 = new Property("some additional key", "New value");
        $property3->comment = array("Even another property comment");
        $section2->addProperty($property3);

        $ini2->addSection($section2);

        $ini->merge($ini2);

        $ini->save();

        $ini3 = new Pini($filename);

        $section3 = $ini3->getSection("some section");

        $this->assertEquals(array("A section comment"), $section3->comment);

        $property4 = $section3->getProperty("some key");
        $property5 = $section3->getProperty("some other key");
        $property6 = $section3->getProperty("some additional key");

        $this->assertEmpty($property4->comment);
        $this->assertEquals(array("Another property comment"), $property5->comment);
        $this->assertEquals(array("Even another property comment"), $property6->comment);

        $this->assertEquals("Replaced value", $property4->value);
        $this->assertEquals("This value will not be replaced", $property5->value);
        $this->assertEquals("New value", $property6->value);

        unlink($filename);
    }

    public function testGetSection()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $this->assertInstanceOf("com\\selfcoders\\pini\\Section", $ini->getSection("my section"));
    }

    public function testGetNotExistingSection()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $this->assertNull($ini->getSection("not existing section"));
    }

    public function testGetProperty()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $section = $ini->getSection("my section");

        $this->assertInstanceOf("com\\selfcoders\\pini\\Property", $section->getProperty("some key"));
    }

    public function testGetNotExistingProperty()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $section = $ini->getSection("my section");

        $this->assertNull($section->getProperty("not existing key"));
    }

    public function testGetPropertyValue()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $section = $ini->getSection("my section");

        $this->assertEquals("some value", $section->getPropertyValue("some key"));
    }

    public function testGetNotExistingPropertyValue()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $section = $ini->getSection("my section");

        $this->assertNull($section->getPropertyValue("not existing key"));
    }

    public function testGetPropertyValueWithDefault()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $section = $ini->getSection("my section");

        $this->assertEquals("some value", $section->getPropertyValue("some key", "fallback value"));
    }

    public function testGetNotExistingPropertyValueWithDefault()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $section = $ini->getSection("my section");

        $this->assertEquals("fallback value", $section->getPropertyValue("not existing key", "fallback value"));
    }

    public function testGetPropertyFromDefaultSection()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $section = $ini->getDefaultSection();

        $this->assertEquals("value in default section", $section->getPropertyValue("some key"));
    }

    public function testEmptySection()
    {
        $ini = new Pini(__DIR__ . "/../../../examples/example.ini");

        $this->assertCount(4, $ini->sections);
        $this->assertCount(3, $ini->getNonEmptySections());

        $this->assertTrue($ini->getSection("my section")->hasProperties());
        $this->assertFalse($ini->getSection("empty")->hasProperties());
    }
}