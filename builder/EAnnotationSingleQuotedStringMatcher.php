<?php
class EAnnotationSingleQuotedStringMatcher extends ERegexMatcher
{

	public function __construct()
	{
		parent::__construct("'([^']*)'");
	}

	protected function process($matches)
	{
		return $matches[1];
	}
}