<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

class ConstantMatcher extends RegexMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
{

	private $constant;

	public function __construct($regex, $constant)
	{
		parent::__construct($regex);
		$this->constant = $constant;
	}

	protected function process($matches)
	{
		return $this->constant;
	}

}
