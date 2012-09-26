<?php

/**
 * This annotation indicates internationallized fields
 *
 * @author Piotr
 */
class I18NAnnotation extends EModelMetaAnnotation
{
	public $value = true;
	public $allowDefault = false;
	public function init()
	{
		$this->_meta->{$this->name}->i18n = $this->value;
		$this->_meta->{$this->name}->i18nAllowDefault = $this->allowDefault;
		$this->_meta->{$this->name}->direct = false;
	}
}