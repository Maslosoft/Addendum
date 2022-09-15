<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link https://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Reflection;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Interfaces\AnnotatedReflectorInterface;
use Maslosoft\Addendum\Utilities\ConflictChecker;
use ReflectionClass;
use ReflectionProperty;

class ReflectionAnnotatedProperty extends ReflectionProperty implements AnnotatedReflectorInterface
{

	/**
	 * Annotations collection
	 * @var AnnotationsCollection
	 */
	private AnnotationsCollection $annotations;

	/**
	 * Addendum instance
	 * @var ?Addendum
	 */
	private ?Addendum $addendum = null;

	public function __construct($class, $name, Addendum $addendum = null)
	{
		parent::__construct($class, $name);
		$this->annotations = (new Builder($addendum))->build($this);
		$this->addendum = $addendum;
		ConflictChecker::check($this, $this->annotations);
	}

	public function hasAnnotation($class): bool
	{
		return $this->annotations->hasAnnotation($class);
	}

	public function getAnnotation($annotation)
	{
		return $this->annotations->getAnnotation($annotation);
	}

	public function getAnnotations(): array
	{
		return $this->annotations->getAnnotations();
	}

	public function getAllAnnotations($restriction = false): array
	{
		return $this->annotations->getAllAnnotations($restriction);
	}

	public function getDeclaringClass(): ReflectionClass
	{
		$class = parent::getDeclaringClass();
		return new ReflectionAnnotatedClass($class->name, $this->addendum);
	}

}
