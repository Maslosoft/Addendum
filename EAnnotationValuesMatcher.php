<?php
class EAnnotationValuesMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new EAnnotationTopValueMatcher);
		$this->add(new EAnnotationHashMatcher);
	}
}