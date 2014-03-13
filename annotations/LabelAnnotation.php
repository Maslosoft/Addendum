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
		// Note: Translation cannot be done here, as it depends on language, and it is cached
		// $this->value = Yii::t('', $this->value);

		$this->_entity->label = $this->value;
	}

	public function __toString()
	{
		return $this->value;
	}
}