<?php
class EAnnotationIntegerMatcher extends ERegexMatcher
{

	public function __construct()
	{
		parent::__construct("-?[0-9]*");
	}

	protected function process($matches)
	{
		return (int)$matches[0];
	}
}