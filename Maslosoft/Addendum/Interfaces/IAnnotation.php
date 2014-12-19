<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IAnnotation
{

	/**
	 * Construct class
	 * @param mixed $data Raw annotations data
	 * @param ReflectionClass|ReflectionMethod|ReflectionProperty|bool $target
	 */
	public function __construct($data = [], $target = false);

	/**
	 * Set working component instance
	 * @param object $component
	 */
	public function setComponent($component);

	/**
	 * TODO Rename it, as it returns array of properties set by annotation, excluding those defined in annotation class
	 */
	public function getUndefinedProperties();

	/**
	 * Init annoattion
	 */
	public function init();

	/**
	 * Convert to array
	 * @return mixed[]
	 */
	public function toArray();
}
