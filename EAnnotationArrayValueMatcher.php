<?php
class EAnnotationArrayValueMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new EAnnotationValueInArrayMatcher);
		$this->add(new EAnnotationPairMatcher);
	}
}