<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Exceptions\ConfigurationException;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;

/**
 * MatcherConfig
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MatcherConfig
{

	public function __construct($config)
	{
		if(!isset($config['addendum']))
		{
			throw new ConfigurationException('Matcher plugins require `addendum`');
		}
		if(!isset($config['reflection']))
		{
			throw new ConfigurationException('Matcher plugins require `reflectionj`');
		}
		$this->addendum = $config['addendum'];
		$this->reflection = $config['reflection'];
	}

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	public $addendum = null;

	/**
	 *
	 * @var ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty
	 */
	public $reflection = null;

}
