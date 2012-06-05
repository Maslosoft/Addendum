<?php

/**
 * Description of BehaviorAnnotation
 *
 * @author Piotr
 */
class BehaviorAnnotation extends EAnnotation
{
	public function init()
	{
		$this->component->attachBehavior($this->value, Yii::createComponent($this->value));
	}
}