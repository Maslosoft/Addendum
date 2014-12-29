<?php

namespace Maslosoft\Addendum\Matcher;

abstract class CompositeMatcher
{

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
