<?php
namespace com\selfcoders\pini;

require_once __DIR__ . "/Section.php";
require_once __DIR__ . "/Property.php";

class Pini
{
	/**
	 * @var string The filename which should be used by default in load() and save() if not specified
	 */
	private $filename;
	/**
	 * @var array An array containing all PiniSection instances
	 */
	public $sections;
	/**
	 * @var string The name of the currently parsing section
	 */
	private $currentSection;
	/**
	 * @var array The content of the currently reading comment block
	 */
	private $commentBlock;

	public function __construct($filename = null)
	{
		$this->filename = $filename;

		$this->init();
		$this->load();
	}

	/**
	 * Initialize the content of the ini file
	 *
	 * Note: This will remove all sections from the ini file!
	 */
	private function init()
	{
		$this->commentBlock = array();
		$this->sections = array();
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
		if (!$line)
		{
			return;
		}

		// This line is a comment -> skip it
		if ($line[0] == ";")
		{
			$this->commentBlock[] = substr($line, 1);
			return;
		}

		// Parse a section in format "[name]"
		if ($line[0] == "[" and $line[strlen($line) - 1] == "]")
		{
			$this->currentSection = new Section(substr($line, 1, strlen($line) - 2), $this->commentBlock);
			$this->addSection($this->currentSection);

			$this->commentBlock = array();
			return;
		}

		// Parse the line in format "key = value"
		list($key, $value) = explode("=", $line, 2);

		$key = trim($key);
		$value = trim($value);

		// No section defined yet -> Create a new default section without a name
		if (!$this->currentSection)
		{
			$this->currentSection = new Section();
			$this->addSection($this->currentSection);
		}

		// The key of key-array pairs end with "[]" (e.g. value[])
		if (substr($key, -2) == "[]")
		{
			// This is a key-array pair (a key with multiple values)
			$key = substr($key, 0, -2);

			$property = $this->currentSection->getProperty($key);
			if (!$property)
			{
				$property = new Property($key, array());
				$this->currentSection->addProperty($property);
			}

			$property->value[] = $value;
		}
		else
		{
			// This is a normal key-value pair
			$property = $this->currentSection->getProperty($key);
			if (!$property)
			{
				$property = new Property($key);
				$this->currentSection->addProperty($property);
			}

			$property->value = $value;
		}

		$property->comment = $this->commentBlock;
		$this->commentBlock = array();
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
		if (!isset($this->sections[$name]))
		{
			return null;
		}

		return $this->sections[$name];
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
		foreach ($otherInstance->sections as $section)
		{
			if (isset($this->sections[$section->name]))
			{
				/**
				 * @var $thisSection Section
				 */
				$thisSection = $this->sections[$section->name];

				$thisSection->merge($section);
			}
			else
			{
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
		if ($filename == null)
		{
			$filename = $this->filename;
			if ($filename == null)
			{
				return false;
			}
		}

		if (!file_exists($filename))
		{
			return false;
		}

		$file = fopen($filename, "r");
		if (!$file)
		{
			return false;
		}

		$this->init();

		while (($line = fgets($file)) !== false)
		{
			$this->parseLine($line);
		}

		fclose($file);

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
		if ($filename == null)
		{
			$filename = $this->filename;
			if ($filename == null)
			{
				return false;
			}
		}

		$file = fopen($filename, "w");
		if (!$file)
		{
			return false;
		}

		/**
		 * @var $section Section
		 */
		foreach ($this->sections as $section)
		{
			foreach ($section->comment as $commentLine)
			{
				fputs($file, ";" . $commentLine . "\n");
			}

			fputs($file, "[" . $section->name . "]\n");

			/**
			 * @var $property Property
			 */
			foreach ($section->properties as $property)
			{
				foreach ($property->comment as $commentLine)
				{
					fputs($file, ";" . $commentLine . "\n");
				}

				if (is_array($property->value))
				{
					foreach ($property->value as $arrayValue)
					{
						fputs($file, $property->name . "[] = " . $arrayValue . "\n");
					}
				}
				else
				{
					fputs($file, $property->name . " = " . $property->value . "\n");
				}
			}
		}

		fclose($file);

		return true;
	}
}