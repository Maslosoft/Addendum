<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

use Maslosoft\Addendum\Exceptions\ParseException;
use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;
use Maslosoft\Addendum\Matcher\Helpers\Processor;

class AnnotationsMatcher implements MatcherInterface
{

	use Traits\PluginsTrait;

	protected function process($string)
	{
		return Processor::process($this, $string);
	}

	public function matches($string, &$annotations)
	{
		$string = $this->process($string);
		$annotations = [];
		$annotationMatcher = new AnnotationMatcher;
		$annotationMatcher->setPlugins($this->getPlugins());
		while (true)
		{
			if (preg_match('~\s(?=@)~', $string, $matches, PREG_OFFSET_CAPTURE))
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
				$srcString = $string;
				$string = substr($string, $length);
				list($name, $params) = $data;
				$annotations[$name][] = $params;

				// If have some params, match should be fine
				if (!empty($params))
				{
					continue;
				}

				// Check if we should have some match
				$stringParams = trim(preg_split("~[\r\n]~", $srcString)[0], '() ');

				if (strlen($stringParams) > $length)
				{
					$msgParams = [
						$name,
						$stringParams
					];
					$msg = vsprintf('Could not parse params `%s` annotation near `%s`', $msgParams);
					throw new ParseException($msg);
				}
			}
		}
	}

}
