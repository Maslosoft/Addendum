<?php

namespace Maslosoft\Addendum\Matcher;

class AnnotationMatcher extends SerialMatcher
{

	protected function build()
	{
		$this->add(new RegexMatcher('@'));
		$this->add(new RegexMatcher('[A-Z][a-zA-Z0-9\\\_]*'));
		$this->add(new ParametersMatcher);
	}

	protected function process($results)
	{
		return array($results[1], $results[2]);
	}

}
