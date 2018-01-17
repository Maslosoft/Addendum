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

use Maslosoft\Addendum\Exceptions\MatcherException;
use Maslosoft\Addendum\Matcher\Traits\PluginsTrait;

class RegexMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
{
	use PluginsTrait;

	protected $regex;

	public function __construct($regex)
	{
		$this->regex = $regex;
	}

	public function matches($string, &$value)
	{
		$matches = [];
		$matched = preg_match("/^{$this->regex}/", $string, $matches);
		if ($matched)
		{
			$value = $this->process($matches);
			return strlen($matches[0]);
		}
		if (false === $matched)
		{
			$params = [
				$this->regex,
				$string
			];
			throw new MatcherException(vsprintf('Could not interpret matcher regex: `%s`, When processing "%s". ', $params), preg_last_error());
		}
		$value = false;
		return false;
	}

	protected function process($matches)
	{
		return $matches[0];
	}

}
