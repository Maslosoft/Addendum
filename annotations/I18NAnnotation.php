<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * This annotation indicates internationallized fields
 * @template I18N
 * @author Piotr
 */
class I18NAnnotation extends MetaAnnotation
{

	public $value = true;
	public $allowDefault = false;
	public $allowAny = false;

	public function init()
	{
		if ($this->allowDefault && $this->allowAny)
		{
			throw new InvalidArgumentException(sprintf('Arguments "allowDefault" and "allowAny" for element "%s" in class "%s" cannot be both set true', $this->name, $this->_meta->type()->name));
		}
		/** @todo Set _entity->i18n to $this and remove other options */
		$this->_entity->i18n = $this->value;
		$this->_entity->i18nAllowDefault = $this->allowDefault;
		$this->_entity->i18nAllowAny = $this->allowAny;
		$this->_entity->direct = false;
	}

}
