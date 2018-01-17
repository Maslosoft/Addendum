<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link https://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

class SimpleSerialMatcher extends SerialMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
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
