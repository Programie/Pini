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

	/**
	 * @param string $name The name for the section
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
	 * @param PiniProperty $property The property to add
	 */
	public function addProperty(PiniProperty $property)
	{
		$this->properties[$property->name] = $property;
	}

	/**
	 * Get the instance of the specified property.
	 *
	 * @param string $name The name of the property
	 * @return null|PiniProperty The property or null if not found
	 */
	public function getProperty($name)
	{
		if (!isset($this->properties[$name]))
		{
			return null;
		}

		return $this->properties[$name];
	}

	/**
	 * Merge the specified section into this section.
	 *
	 * Note: Any existing property with the same name will be overwritten!
	 *
	 * @param PiniSection $otherSection The section which should be merged
	 */
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