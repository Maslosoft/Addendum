<?php

/**
 * Description of ClassTags
 *
 * @author Piotr Maselkowski <piotr at maselkowski dot pl>
 */
class EAnnotations extends CComponent
{
	protected $_tagPaths = array();
	protected $_tags = null;
	protected $_info = null;

	/**
	 * Get annotations from class alias, name or instance
	 * @param string $alias
	 */
	public function get($alias)
	{
		if(is_object($alias))
		{
			$this->_info = new ReflectionObject($alias);
		}
		elseif(false !== strstr('.', $alias))
		{
			$this->_info = new ReflectionClass($alias);
		}
		else
		{
			Yii::import($alias);
			$this->_info = new ReflectionClass($this->getClassName($alias));
		}
		$class = new EAnnotationClass($this, $this->_info);
		foreach($this->extractTags($this->_info->getDocComment()) as $tag => $values)
		{
			foreach($values as $value)
			{
				
				$class->addTag($tag, $value);
			}
		}
		foreach($this->_info->getProperties() as $prop)
		{
			if(false)
			$prop = new ReflectionProperty($class, $name);
			if($prop->isPublic())
			{
				$name = $prop->getName();
			$class->fields->$name = new EAnnotationField();
			echo $prop->name;
			var_dump($prop);
			}
			
		}
	}

	protected function extractTags($comment)
	{
		$matches = array();
		$tags = array();
		preg_match_all('~@(\w+)\s+(.+)~', $comment, $matches);
		foreach($matches[1] as $i => $name)
		{
			$tags[$name][] = $matches[2][$i];
		}
		return $tags;
	}

	public function setTagPaths($paths)
	{
		foreach($paths as $alias)
		{
			$this->_tagPaths[$alias] = Yii::getPathOfAlias($alias);
		}
	}

	public function getTagPaths($paths)
	{
		return $this->_tagPaths;
	}

	public function addTagPath($alias)
	{
		$this->_tagPaths[$alias] = Yii::getPathOfAlias($alias);
	}

	public function getTags($reload = false)
	{
		if(null === $this->_tags || $reload)
		{
			foreach($this->_tagPaths as $alias => $path)
			{
				$dir = new DirectoryIterator($path);
				foreach($dir as $file)
				{
					$name = $file->getFilename();

					if($file->isFile() && preg_match('~.+Attr\.php$~', $name))
					{
						$className = preg_replace('~\.php$~', '', $name);
						$classPath = "$alias.$className";
						Yii::import($classPath, true);
						$class = new ReflectionClass($className);
						if(false === $class->isInstantiable())
						{
							continue;
						}
						$classPattern = '~(Field|Class|Method)Attr$~';
						
						$tagName = lcfirst(preg_replace($classPattern, '', $className));
						$typeMatch = array();
						if(false !== preg_match_all($classPattern, $className, $typeMatch))
						{
							$type = $typeMatch[1][0];
							if($class->isSubclassOf("EAnnotation{$type}Attr"))
							{
								$this->_tags[strtolower($type)][$tagName] = $classPath;
//								echo "$className of tag '$tagName' is $type Attribute<br>";
							}
						}
					}
				}
			}
		}
//		var_dump($this->_tags);
		return $this->_tags;
	}

	public function hasTag($type, $name)
	{
		return isset($this->_tags[$type][$name]);
	}

	public function getClassName($alias)
	{
		if(false === strstr($alias, '.'))
		{
			return $alias;
		}
		if(Yii::getPathOfAlias($alias))
		{
			return array_pop(explode('.', $alias));
		}
		else
		{
			return false;
		}
	}
}