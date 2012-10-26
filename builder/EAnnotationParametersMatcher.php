<?php
class EAnnotationParametersMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new EConstantMatcher('', array()));
		$this->add(new EConstantMatcher('\(\)', array()));
		$params_matcher = new ESimpleSerialMatcher(1);
		$params_matcher->add(new ERegexMatcher('\(\s*'));
		$params_matcher->add(new EAnnotationValuesMatcher);
		$params_matcher->add(new ERegexMatcher('\s*\)'));
		$this->add($params_matcher);
	}
}