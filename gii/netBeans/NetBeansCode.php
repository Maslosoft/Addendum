<?php

require_once __DIR__ . '/../../EAddendum.php';

/**
 * NetBeansCode
 *
 * @author Piotr
 */
class NetBeansCode extends CCodeModel
{

	public function prepare()
	{
		$util = new EAnnotationUtility();
		$util->generateNetbeansHelpers();

		$path = sprintf('%s/annotations.zip', Yii::getPathOfAlias('application.runtime'));
		$code = '<binary file>';

		$this->files[] = new CCodeFile($path, $code);
	}

}