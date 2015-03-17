<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Gazebo\PluginContainer;

/**
 * MatcherConfig
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MatcherConfig extends PluginContainer
{

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
