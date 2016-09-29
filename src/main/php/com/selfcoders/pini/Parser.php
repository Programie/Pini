<?php
namespace com\selfcoders\pini;

class Parser
{
    /**
     * @var array
     */
    private $commentBlock = array();
    /**
     * @var Section
     */
    private $currentSection;
    /**
     * @var Pini
     */
    private $pini;

    public function __construct(Pini $pini)
    {
        $this->pini = $pini;
    }

    public function readFromFile($fileHandle)
    {
        while (($line = fgets($fileHandle)) !== false) {
            $this->parseLine($line);
        }
    }

    /**
     * Parse the given line.
     *
     * @param string $line The line to parse
     */
    private function parseLine($line)
    {
        $line = trim($line);

        // Ignore empty lines
        if (!strlen($line)) {
            return;
        }

        // This line is a comment -> write it into the current comment block
        if ($line[0] == ";") {
            $this->commentBlock[] = substr($line, 1);
            return;
        }

        // Parse a section in format "[name]"
        if ($line[0] == "[" and $line[strlen($line) - 1] == "]") {
            $this->currentSection = new Section(substr($line, 1, strlen($line) - 2), $this->commentBlock);
            $this->pini->addSection($this->currentSection);

            $this->commentBlock = array();
            return;
        }

        // Parse the line in format "key = value"
        list($key, $value) = explode("=", $line, 2);

        $key = trim($key);
        $value = trim($value);

        // No section defined yet -> Create a new default section without a name
        if (!$this->currentSection) {
            $this->currentSection = new Section;
            $this->pini->addSection($this->currentSection);
        }

        // The key of key-array pairs end with "[]" (e.g. value[])
        if (substr($key, -2) == "[]") {
            $this->addArrayProperty(substr($key, 0, -2), $value);
        } else {
            $this->addProperty($key, $value);
        }
    }

    private function addArrayProperty($key, $value)
    {
        $property = $this->currentSection->getProperty($key);
        if ($property === null) {
            $property = new Property($key, array());
            $this->currentSection->addProperty($property);
        }

        $property->value[] = $value;
        $property->comment = $this->commentBlock;

        $this->commentBlock = array();
    }

    private function addProperty($key, $value)
    {
        $property = $this->currentSection->getProperty($key);
        if (!$property) {
            $property = new Property($key);
            $this->currentSection->addProperty($property);
        }

        $property->value = $value;
        $property->comment = $this->commentBlock;

        $this->commentBlock = array();
    }
}