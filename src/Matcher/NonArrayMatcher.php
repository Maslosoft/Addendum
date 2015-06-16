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

use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;

/**
 * Matches any value, except arrays
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NonArrayMatcher extends ParallelMatcher implements MatcherInterface
{

	protected function build()
	{
		$this->add((new ConstantMatcher('true', true))->setPlugins($this->getPlugins()));
		$this->add((new ConstantMatcher('false', false))->setPlugins($this->getPlugins()));
		$this->add((new ConstantMatcher('TRUE', true))->setPlugins($this->getPlugins()));
		$this->add((new ConstantMatcher('FALSE', false))->setPlugins($this->getPlugins()));
		$this->add((new ConstantMatcher('NULL', null))->setPlugins($this->getPlugins()));
		$this->add((new ConstantMatcher('null', null))->setPlugins($this->getPlugins()));
		$this->add((new ClassLiteralMatcher)->setPlugins($this->getPlugins()));
		$this->add((new StringMatcher)->setPlugins($this->getPlugins()));
		$this->add((new NumberMatcher)->setPlugins($this->getPlugins()));
		$this->add((new StaticConstantMatcher)->setPlugins($this->getPlugins()));
		$this->add((new GlobalConstantMatcher)->setPlugins($this->getPlugins()));
		$this->add((new NestedAnnotationMatcher)->setPlugins($this->getPlugins()));
	}

}
