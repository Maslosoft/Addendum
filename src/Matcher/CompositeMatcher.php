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

abstract class CompositeMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
{
	use Traits\PluginsTrait;
	protected $matchers = [];
	private $wasConstructed = false;

	public function add($matcher)
	{
		$this->matchers[] = $matcher;
	}

	public function matches($string, &$value)
	{
		if (!$this->wasConstructed)
		{
			$this->build();
			$this->wasConstructed = true;
		}
		return $this->match($string, $value);
	}

	protected function build()
	{

	}

	abstract protected function match($string, &$value);
}
