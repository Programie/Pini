<?php
namespace com\selfcoders\pini;

class Pini
{
    /**
     * @var string The filename which should be used by default in load() and save() if not specified
     */
    private $filename;
    /**
     * @var Section[] An array containing all Section instances
     */
    public $sections = array();

    public function __construct($filename = null)
    {
        $this->filename = $filename;

        $this->load();
    }

    /**
     * Add the given section.
     *
     * @param Section $section The instance of the section
     */
    public function addSection(Section $section)
    {
        $this->sections[$section->name] = $section;
    }

    /**
     * Get the instance of the specified section.
     *
     * @param string $name The name of the section to retrieve
     * @return null|Section The section or null if not found
     */
    public function getSection($name)
    {
        if (!isset($this->sections[$name])) {
            return null;
        }

        return $this->sections[$name];
    }

    /**
     * Get the instance of the default section.
     *
     * The default section contains all properties outside of any section.
     *
     * A new section will be created if there is no default section yet.
     *
     * @return Section The instance of the default section
     */
    public function getDefaultSection()
    {
        $section = $this->getSection("");
        if ($section === null) {
            $section = new Section;
            $this->addSection($section);
        }

        return $section;
    }

    /**
     * @return Section[]
     */
    public function getNonEmptySections()
    {
        $sections = array();

        foreach ($this->sections as $section) {
            if (!$section->hasProperties()) {
                continue;
            }

            $sections[] = $section;
        }

        return $sections;
    }

    /**
     * Remove all sections.
     *
     * Note: This will not destroy the section instances.
     */
    public function removeAllSections()
    {
        $this->sections = array();
    }

    /**
     * Merge the sections of the given Pini instance into this instance.
     *
     * @param Pini $otherInstance The Pini instance of which the sections should be merged into this instance
     */
    public function merge(Pini $otherInstance)
    {
        foreach ($otherInstance->sections as $section) {
            if (isset($this->sections[$section->name])) {
                /**
                 * @var $thisSection Section
                 */
                $thisSection = $this->sections[$section->name];

                $thisSection->merge($section);
            } else {
                $this->addSection($section);
            }
        }
    }

    /**
     * Load the content of the ini file.
     *
     * @param null|string $filename The name of the file from which the content should be loaded or null to load it from the initially given filename
     *
     * @return bool true if the file was loaded successfully, false otherwise
     */
    public function load($filename = null)
    {
        if ($filename == null) {
            $filename = $this->filename;
            if ($filename == null) {
                return false;
            }
        }

        if (!file_exists($filename)) {
            return false;
        }

        $fileHandle = fopen($filename, "r");
        if ($fileHandle === false) {
            return false;
        }

        $this->removeAllSections();

        $parser = new Parser($this);

        $parser->readFromFile($fileHandle);

        fclose($fileHandle);

        return true;
    }

    /**
     * Save the content of the ini file.
     *
     * @param null|string $filename The name of the file in which the content should be saved or null to overwrite the initially given file
     *
     * @return bool true if the file was written successfully, false otherwise
     */
    public function save($filename = null)
    {
        if ($filename == null) {
            $filename = $this->filename;
            if ($filename == null) {
                return false;
            }
        }

        $file = fopen($filename, "w");
        if (!$file) {
            return false;
        }

        fwrite($file, $this);

        fclose($file);

        return true;
    }

    /**
     * Get this instance as a string in INI format.
     *
     * @return string
     */
    public function __toString()
    {
        $string = $this->getDefaultSection();

        foreach ($this->sections as $section) {
            // Skip default section as this has been already added to the string above.
            if ($section->isDefaultSection()) {
                continue;
            }

            $string .= $section;
        }

        return $string;
    }
}