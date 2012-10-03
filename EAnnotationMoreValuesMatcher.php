<?php
class EAnnotationMoreValuesMatcher extends ESimpleSerialMatcher
{

	protected function build()
	{
		$this->add(new EAnnotationArrayValueMatcher);
		$this->add(new ERegexMatcher('\s*,\s*'));
		$this->add(new EAnnotationArrayValuesMatcher);
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