<?php

/**
 * This utility extract i18n labels, descriptions etc.
 *
 * @author Piotr
 */
class EAnnotationUtilityI18N extends CWidget
{

	private $_file = [];

	public function generate($searchAliases = [], $outputAlias = null)
	{
		$paths = [];
		foreach ($searchAliases as $alias)
		{
			$paths[] = Yii::getPathOfAlias($alias);
		}
		$this->_file[] = '<?php';
		EAnnotationUtility::fileWalker(Yii::app()->addendum->i18nAnnotations, [$this, 'walk'], $paths);
		if(null === $outputAlias)
		{
			file_put_contents(sprintf('%s/annotated-labels.php', Yii::getPathOfAlias('autogen')), implode("\n", $this->_file));
		}
		else
		{
			file_put_contents(sprintf('%s/annotated-labels.php', Yii::getPathOfAlias($outputAlias)), implode("\n", $this->_file));
		}
		return $this->_file;
	}

	public function walk($file)
	{
		$annotations = EAnnotationUtility::rawAnnotate($file);

		foreach ($annotations['class'] as $type => $annotation)
		{
			$this->extract($type, $annotation, $file);
		}

		foreach (['methods', 'fields'] as $entityType)
		{
			foreach ($annotations[$entityType] as $name => $entity)
			{
				foreach ($entity as $type => $annotation)
				{
					$this->extract($type, $annotation, $file, $name);
				}
			}
		}
	}

	public function extract($type, $annotation, $file, $name = null)
	{
		if (in_array($type, Yii::app()->addendum->i18nAnnotations))
		{
			foreach ($annotation as $values)
			{
				if(!isset($values['value']))
				{
					continue;
				}
				$value = $values['value'];
				if(!$value)
				{
					continue;
				}
				$alias = EAnnotationUtility::getAliasOfPath($file);
				$parts = explode('.', $alias);
				$class = array_pop($parts);
				if (null === $name)
				{
					$name = $class;
				}
				$w = "'";
				if(strstr($value, "'") !== false)
				{
					$w = '"';
				}
				$this->_file[] = $this->render('i18nEntity', [
					'alias' => $alias,
					'class' => $class,
					'name' => $name,
					'value' => $value,
					'w' => $w
						], true);
			}
		}
	}

}
