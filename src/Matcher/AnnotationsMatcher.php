<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

class AnnotationsMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\IMatcher
{

	use Traits\PluginsTrait;

	public function matches($string, &$annotations)
	{
		$annotations = [];
		$annotationMatcher = new AnnotationMatcher;
		$annotationMatcher->setPlugins($this->getPlugins());
		while (true)
		{
			if (preg_match('/\s(?=@)/', $string, $matches, PREG_OFFSET_CAPTURE))
			{
				$offset = $matches[0][1] + 1;
				$string = substr($string, $offset);
			}
			else
			{
				return; // no more annotations
			}

			if (($length = $annotationMatcher->matches($string, $data)) !== false)
			{
				$string = substr($string, $length);
				list($name, $params) = $data;
				$annotations[$name][] = $params;
			}
		}
	}

}
