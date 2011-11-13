<?php
class EAnnotationStringMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new EAnnotationSingleQuotedStringMatcher);
		$this->add(new EAnnotationDoubleQuotedStringMatcher);
	}
}