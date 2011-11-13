<?php
class EAnnotationMatcher extends ESerialMatcher
{

	protected function build()
	{
		$this->add(new ERegexMatcher('@'));
		$this->add(new ERegexMatcher('[A-Z][a-zA-Z0-9_]*'));
		$this->add(new EAnnotationParametersMatcher);
	}

	protected function process($results)
	{
		return array($results[1], $results[2]);
	}
}