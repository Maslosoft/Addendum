<?php
class EAnnotationPairMatcher extends ESerialMatcher
{

	protected function build()
	{
		$this->add(new EAnnotationKeyMatcher);
		$this->add(new ERegexMatcher('\s*=\s*'));
		$this->add(new EAnnotationValueMatcher);
	}

	protected function process($parts)
	{
		return array($parts[0] => $parts[2]);
	}
}