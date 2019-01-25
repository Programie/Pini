<?php
namespace com\selfcoders\pini;

class Section
{
    /**
     * @var string The name of the section
     */
    public $name;
    /**
     * @var array A list of comment lines of the section
     */
    public $comment;
    /**
     * @var Property[] A list of Property instances added to the section
     */
    public $properties;

    /**
     * @param string $name The name for the section, an empty name will make this the default section
     * @param array $comment A list of comment lines for this section
     */
    public function __construct($name = "", $comment = array())
    {
        $this->name = $name;
        $this->comment = $comment;

        $this->properties = array();
    }

    /**
     * Add the specified property to the section.
     *
     * Note: Any existing property with the same name will be overwritten!
     *
     * @param Property $property The property to add
     */
    public function addProperty(Property $property)
    {
        $this->properties[$property->name] = $property;
    }

    /**
     * Get the instance of the specified property.
     *
     * @param string $name The name of the property
     * @return null|Property The property or null if not found
     */
    public function getProperty($name)
    {
        if (!isset($this->properties[$name])) {
            return null;
        }

        return $this->properties[$name];
    }

    /**
     * Get the value of the specified property.
     *
     * This is a shortcut for getProperty()->value.
     *
     * @param string $name The name of the property
     * @param mixed|null $defaultValue The default value to return if the property does not exist
     *
     * @return mixed The property value or value specified in $defaultValue if the property does not exist
     */
    public function getPropertyValue($name, $defaultValue = null)
    {
        $property = $this->getProperty($name);
        if ($property === null) {
            return $defaultValue;
        }

        return $property->value;
    }

    /**
     * Merge the specified section into this section.
     *
     * Note: Any existing property with the same name will be overwritten!
     *
     * @param Section $otherSection The section which should be merged
     */
    public function merge(Section $otherSection)
    {
        foreach ($otherSection->properties as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * Check whether this section is the default section (properties are not in a specific section).
     *
     * @return bool
     */
    public function isDefaultSection()
    {
        return $this->name === "";
    }

    /**
     * @return bool
     */
    public function hasProperties()
    {
        return !empty($this->properties);
    }

    /**
     * Write all properties of this section to the given file handle.
     *
     * @param resource $fileHandle The file handle to write to (e.g. returned by fopen())
     *
     * @deprecated Write this instance to a file using magic method "__toString()" which will then also include the section header and comment (fwrite($handle, $section)).
     */
    public function writePropertiesToFile($fileHandle)
    {
        foreach ($this->properties as $property) {
            fwrite($fileHandle, $property);
        }
    }

    /**
     * Get this section as a string in INI format.
     *
     * @return string
     */
    public function __toString()
    {
        $string = "";

        foreach ($this->comment as $commentLine) {
            $string .= sprintf(";%s\n", $commentLine);
        }

        $string .= sprintf("[%s]\n", $this->name);

        foreach ($this->properties as $property) {
            $string .= $property;
        }

        return $string;
    }
}