<?php
class Ini
{
	/**
	 * @var array An array containing all sections and their keys
	 * @see getData for more information
	 */
	private $data;
	/**
	 * @var string The name of the currently parsing section
	 */
	private $currentSection;

	public function __construct($filename)
	{
		$this->data = array();

		$file = fopen($filename, "r");
		if (!$file)
		{
			return;
		}

		while (($line = fgets($file)) !== false)
		{
			$this->parseLine($line);
		}

		fclose($file);
	}

	/**
	 * Parse the given line.
	 *
	 * @param string $line The line to parse
	 */
	private function parseLine($line)
	{
		$line = trim($line);

		// This line is a comment -> skip it
		if ($line[0] == ";")
		{
			return;
		}

		// Parse a section in format "[name]"
		if ($line[0] == "[" and $line[strlen($line) - 1] == "]")
		{
			$this->currentSection = substr($line, 1, strlen($line) - 2);
			return;
		}

		// Parse the line in format "key = value"
		list($key, $value) = explode("=", $line, 2);

		$key = trim($key);
		$value = trim($value);

		if (!isset($this->data[$this->currentSection]))
		{
			$this->data[$this->currentSection] = array();
		}

		// The key of key-array pairs end with "[]" (e.g. value[])
		if (substr($key, -2) == "[]")
		{
			// This is a key-array pair (a key with multiple values)
			$key = substr($key, 0, -2);

			if (!isset($this->data[$this->currentSection][$key]) or !is_array($this->data[$this->currentSection][$key]))
			{
				$this->data[$this->currentSection][$key] = array();
			}

			$this->data[$this->currentSection][$key][] = $value;
		}
		else
		{
			// This is a normal key-value pair
			$this->data[$this->currentSection][$key] = $value;
		}
	}

	/**
	 * Get the complete array containing all sections and keys of the parsed ini file.
	 *
	 * The returned array has the following structure:
	 * array
	 * (
	 *   "section1" => array
	 *   (
	 *     "key" => "some value",
	 *     "another key" => "another value"
	 *   ),
	 *   "another section" => array
	 *   (
	 *     "key" => "value of another section",
	 *     "yet another key" => "value",
	 *     "some array" => array
	 *     (
	 *       "some value in array",
	 *       "another value in array"
	 *     )
	 *   )
	 * )
	 *
	 * @return array The complete data of the parsed ini file
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Get the value of the specified key in the specified section.
	 *
	 * @param string $section The name of the section from which the key should be retrieved
	 * @param string $key The name of the key of which the value should be retrieved
	 *
	 * @return null|string The value of the key in the section
	 */
	public function getValue($section, $key)
	{
		if (!isset($this->data[$section]))
		{
			return null;
		}

		if (!isset($this->data[$section][$key]))
		{
			return null;
		}

		return $this->data[$section][$key];
	}

	/**
	 * Merge the data of the given Ini instance into this instance.
	 *
	 * @param Ini $otherInstance The Ini instance of which the data should be merged into this instance
	 * @param null|string $section An optional section name which should be merged (any other section will be omitted)
	 */
	public function merge(Ini $otherInstance, $section = null)
	{
		$sourceData = $otherInstance->getData();

		if ($section == null)
		{
			$this->data = array_replace_recursive($this->data, $sourceData);
			return;
		}

		if (!isset($sourceData[$section]))
		{
			return;
		}

		if (!isset($this->data[$section]))
		{
			$this->data[$section] = array();
		}

		$this->data[$section] = array_replace_recursive($this->data, $sourceData[$section]);
	}
}