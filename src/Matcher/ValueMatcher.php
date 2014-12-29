<?php

namespace Maslosoft\Addendum\Matcher;

class ValueMatcher extends ParallelMatcher
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
		$this->add(new ArrayMatcher);
		$this->add(new StaticConstantMatcher);
		$this->add(new NestedAnnotationMatcher);
	}

}
