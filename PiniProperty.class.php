<?php
class PiniProperty
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
}