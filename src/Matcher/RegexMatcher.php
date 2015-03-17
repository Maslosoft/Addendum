<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

class RegexMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\IMatcher
{
use Traits\PluginsTrait;
	private $regex;

	public function __construct($regex)
	{
		$this->regex = $regex;
	}

	public function matches($string, &$value)
	{
		$matches = [];
		if (preg_match("/^{$this->regex}/", $string, $matches))
		{
			$value = $this->process($matches);
			return strlen($matches[0]);
		}
		$value = false;
		return false;
	}

	protected function process($matches)
	{
		return $matches[0];
	}

}
