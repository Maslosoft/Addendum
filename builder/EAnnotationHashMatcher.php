<?php
class EAnnotationHashMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new EAnnotationPairMatcher);
		$this->add(new EAnnotationMorePairsMatcher);
	}
}