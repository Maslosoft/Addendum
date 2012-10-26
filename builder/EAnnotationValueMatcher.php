<?php
class EAnnotationValueMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new EConstantMatcher('true', true));
		$this->add(new EConstantMatcher('false', false));
		$this->add(new EConstantMatcher('TRUE', true));
		$this->add(new EConstantMatcher('FALSE', false));
		$this->add(new EConstantMatcher('NULL', null));
		$this->add(new EConstantMatcher('null', null));
		$this->add(new EAnnotationStringMatcher);
		$this->add(new EAnnotationNumberMatcher);
		$this->add(new EAnnotationArrayMatcher);
		$this->add(new EAnnotationStaticConstantMatcher);
		$this->add(new ENestedAnnotationMatcher);
	}
}