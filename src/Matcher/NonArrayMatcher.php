<?php

namespace Maslosoft\Addendum\Matcher;

/**
 * Matches any value, except arrays
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NonArrayMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new ConstantMatcher('true', true));
		$this->add(new ConstantMatcher('false', false));
		$this->add(new ConstantMatcher('TRUE', true));
		$this->add(new ConstantMatcher('FALSE', false));
		$this->add(new ConstantMatcher('NULL', null));
		$this->add(new ConstantMatcher('null', null));
		$this->add(new ClassLiteralMatcher);
		$this->add(new StringMatcher);
		$this->add(new NumberMatcher);
		$this->add(new StaticConstantMatcher);
		$this->add(new NestedAnnotationMatcher);
	}

}
