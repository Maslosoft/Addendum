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

class AnnotationMatcher extends SerialMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\IMatcher
{

	protected function build()
	{
		$this->add((new RegexMatcher('@'))->setPlugins($this->getPlugins()));
		$this->add((new RegexMatcher('[A-Z][a-zA-Z0-9\\\_]*'))->setPlugins($this->getPlugins()));
		$this->add((new ParametersMatcher)->setPlugins($this->getPlugins()));
	}

	protected function process($results)
	{
		return [$results[1], $results[2]];
	}

}
