<?php

/**
 * Label
 * Set translated entity 'label' field
 * @template Label('${text}')
 */
class LabelAnnotation extends EComponentMetaAnnotation
{
	public $value = '';

	public function init()
	{
		// TODO There is no context support yet. Maybe this should be implemented in some kind of configurable callbacks?
		$this->value = Yii::t('', $this->value);

		$this->_entity->label = $this->value;
	}

	public function __toString()
	{
		return $this->value;
	}
}