<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Matcher;

/**
 * ClassLiteralMatcher
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassLiteralMatcher extends RegexMatcher
{
	public function __construct()
	{
		parent::__construct('([A-Z\\\][a-zA-Z0-9_\\\]+)');
	}

	protected function process($matches)
	{
		return $matches[1];
	}
}
