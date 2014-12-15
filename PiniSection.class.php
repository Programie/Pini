<?php
class PiniSection
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
	 * @var array A list of PiniProperty instances added to the section
	 */
	public $properties;

	public function __construct($name = "", $comment = array())
	{
		$this->name = $name;
		$this->comment = $comment;

		$this->properties = array();
	}

	public function addProperty(PiniProperty $property)
	{
		$this->properties[$property->name] = $property;
	}

	public function getProperty($name)
	{
		if (!isset($this->properties[$name]))
		{
			return null;
		}

		return $this->properties[$name];
	}

	public function merge(PiniSection $otherSection)
	{
		/**
		 * @var $property PiniProperty
		 */
		foreach ($otherSection->properties as $property)
		{
			$this->addProperty($property);
		}
	}
}