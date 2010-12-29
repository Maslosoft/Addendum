<?php

/**
 * Description of ClassTags
 *
 * @author Piotr Maselkowski <piotr at maselkowski dot pl>
 */
class EAnnotations extends CComponent
{
	protected $_tagPaths = array();

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

	public function setTagPath($alias)
	{
		$this->_tagPaths[$alias] = Yii::getPathOfAlias($alias);
	}

	protected function getTags()
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
					$tagName = lcfirst(preg_replace('~(Field|Class)Attr$~', '', $className));
					if($class->isSubclassOf('MongoClassAttr'))
					{
						echo "$className of tag '$tagName' is Class Attribute<br>";
					}
					elseif($class->isSubclassOf('MongoFieldAttr'))
					{
						echo "$className of tag '$tagName' is Field Attribute<br>";
					}
				}
			}
		}
	}
}