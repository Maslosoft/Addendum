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

class AnnotationMatcher extends SerialMatcher
{

	protected function build()
	{
		$this->add(new RegexMatcher('@'));
		$this->add(new RegexMatcher('[A-Z][a-zA-Z0-9\\\_]*'));
		$this->add(new ParametersMatcher);
	}

	protected function process($results)
	{
		return [$results[1], $results[2]];
	}

}
