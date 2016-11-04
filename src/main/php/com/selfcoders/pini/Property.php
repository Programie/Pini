<?php
namespace com\selfcoders\pini;

class Property
{
    /**
     * @var string The name of the property
     */
    public $name;
    /**
     * @var string|array The value of the property
     */
    public $value;
    /**
     * @var array A list of comment lines of the property
     */
    public $comment;

    /**
     * @param string $name The name for the property
     * @param string|array $value The value for the property
     */
    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->comment = array();
    }

    /**
     * Get this property as a string in INI format.
     *
     * @return string
     */
    public function __toString()
    {
        $string = "";

        foreach ($this->comment as $commentLine) {
            $string .= sprintf(";%s\n", $commentLine);
        }

        if (is_array($this->value)) {
            foreach ($this->value as $value) {
                $string .= sprintf("%s[] = %s\n", $this->name, $value);
            }
        } else {
            $string .= sprintf("%s = %s\n", $this->name, $this->value);
        }

        return $string;
    }
}