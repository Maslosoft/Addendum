<?php
class EAnnotationArrayMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new EConstantMatcher('{}', array()));
		$values_matcher = new ESimpleSerialMatcher(1);
		$values_matcher->add(new ERegexMatcher('\s*{\s*'));
		$values_matcher->add(new EAnnotationArrayValuesMatcher);
		$values_matcher->add(new ERegexMatcher('\s*}\s*'));
		$this->add($values_matcher);
	}
}