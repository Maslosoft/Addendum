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

namespace Maslosoft\Addendum\Interfaces;

use Maslosoft\Addendum\Collections\Meta;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface MetaAnnotationInterface extends IMetaAnnotation
{

	/**
	 * Set entity name (class name, method name or property name)
	 * @return void
	 */
	public function setName($name);

	/**
	 * Set metada class to be accessible for annotation for init etc. methods
	 * @param Meta $meta
	 * @return void
	 */
	public function setMeta(Meta $meta);

	/**
	 * Set annotatins entity, it can be either class, property, or method
	 * @param AnnotationEntityInterface $entity
	 * @return void
	 */
	public function setEntity(AnnotationEntityInterface $entity);

	/**
	 * This function should be called after all annotations are initialized.
	 * Any code that depends on other annotations can be executed here.
	 * NOTE: This is not ensured to run, its annotations container responsibility to call it.
	 * @return void
	 */
	public function afterInit();
}
