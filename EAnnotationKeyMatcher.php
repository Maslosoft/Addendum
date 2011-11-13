<?php
class EAnnotationKeyMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new ERegexMatcher('[a-zA-Z][a-zA-Z0-9_]*'));
		$this->add(new EAnnotationStringMatcher);
		$this->add(new EAnnotationIntegerMatcher);
	}
}