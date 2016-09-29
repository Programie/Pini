<?php
namespace com\selfcoders\pini;

class Pini
{
    /**
     * @var string The filename which should be used by default in load() and save() if not specified
     */
    private $filename;
    /**
     * @var array An array containing all Section instances
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
        /**
         * @var $section Section
         */
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

        $defaultSection = $this->getDefaultSection();

        foreach ($defaultSection->comment as $commentLine) {
            fputs($file, ";" . $commentLine . "\n");
        }

        $defaultSection->writePropertiesToFile($file);

        /**
         * @var $section Section
         */
        foreach ($this->sections as $section) {
            if ($section->name == "") {
                continue;
            }

            foreach ($section->comment as $commentLine) {
                fputs($file, ";" . $commentLine . "\n");
            }

            fputs($file, "[" . $section->name . "]\n");

            $section->writePropertiesToFile($file);
        }

        fclose($file);

        return true;
    }
}