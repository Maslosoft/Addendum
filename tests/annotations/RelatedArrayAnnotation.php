<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations;

/**
 * RelatedAnnotation
 * Shorthand notation:
 *
 * RelatedArray(Company\Project\Projects, join = {'_id' = 'entity_id'}, sort = {'_id' = 1}, true)
 *
 * Expanded notation:
 *
 * RelatedArray(class = Company\Project\Projects, join = {'_id' => 'entity_id'}, sort = {'_id' = 1}, updatable = true)
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedArrayAnnotation extends RelatedAnnotation
{

	public $class;
	public $join;
	public $updatable;
	public $value;

	public function init()
	{
		$relMeta = $this->_getMeta();
		$relMeta->single = false;
		$relMeta->isArray = true;
		$this->getEntity()->related = $relMeta;
		$this->getEntity()->propagateEvents = true;
		$this->getEntity()->owned = true;
	}

}
