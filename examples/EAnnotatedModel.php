<?php

/**
 * This is some sample base class for models to show how annotations container could be used
 * NOTE: This works in different way than yii get/set methods. If you want to define getter or setter for field,
 * define it public, and name methods yii way. For example @see __get @see __set
 * @author Piotr
 */
abstract class EAnnotatedModel extends CModel
{
	/**
	 * This holds type of this model
	 * @var string
	 */
	public $_class = null;

	/**
	 * Model metadata
	 * @Persistent(false)
	 * @var EComponentMeta
	 */
	private static $_meta = [];

	public function __construct()
	{
		$this->_class = get_class($this);
		$this->meta->initModel($this);
	}

	/**
	 * Support for get accessors for fields
	 * @example Fieldname: testField; get method: getTestField;
	 * @param string $name
	 * @return mixed result of get<fieldName> function
	 */
	public function __get($name)
	{
		if($name == 'meta')
		{
			return $this->getMeta();
		}
		$meta = $this->meta->$name;
		if($meta)
		{
			if($meta->callGet)
			{
				return $this->{$meta->methodGet}();
			}
		}
		return parent::__get($name);
	}

	/**
	 * Support for set accessors for fields
	 * @example Fieldname: testField; set method: setTestField;
	 * @param string $name
	 * @param mixed $value
	 * @return mixed result of get<fieldName> function
	 */
	public function __set($name, $value)
	{
		$meta = $this->meta->$name;
		if($meta)
		{
			if($meta->readonly)
			{
				return '';
			}
			if($meta->callSet)
			{
				return $this->{$meta->methodSet}($value);
			}
		}
		return parent::__set($name, $value);
	}

	/**
	 * Validation rules based on validator annotations
	 * @return mixed[][]
	 */
	public function rules()
	{
		$pattern = '~Validator$~';
		$result = [];
		foreach($this->meta->fields() as $field => $meta)
		{
			foreach($meta as $type => $value)
			{
				if(preg_match($pattern, $type))
				{
					$type = preg_replace($pattern, '', $type);
					$value = (array)$value;
					if(isset($value['class']))
					{
						$type = $value['class'];
						unset($value['class']);
					}
					$result[] = array_merge([$field, $type], $value);
				}
			}
		}
		return $result;
	}

	public function attributeLabels()
	{
		return $this->meta->properties('label');
	}

	public function getMeta()
	{
		if(!isset(self::$_meta[$this->_class]))
		{
			self::$_meta[$this->_class] = Yii::app()->addendum->meta($this);
		}
		return self::$_meta[$this->_class];
	}
}
