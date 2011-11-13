<?php
class EAnnotationMorePairsMatcher extends ESerialMatcher
{

	protected function build()
	{
		$this->add(new EAnnotationPairMatcher);
		$this->add(new ERegexMatcher('\s*,\s*'));
		$this->add(new EAnnotationHashMatcher);
	}

	protected function match($string, &$value)
	{
		$result = parent::match($string, $value);
		return $result;
	}

	public function process($parts)
	{
		return array_merge($parts[0], $parts[2]);
	}
}