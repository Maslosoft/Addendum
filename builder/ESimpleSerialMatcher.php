<?php
class ESimpleSerialMatcher extends ESerialMatcher
{
	private $return_part_index;

	public function __construct($return_part_index = 0)
	{
		$this->return_part_index = $return_part_index;
	}

	public function process($parts)
	{
		return $parts[$this->return_part_index];
	}
}