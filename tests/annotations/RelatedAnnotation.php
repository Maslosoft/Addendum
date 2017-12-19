<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Addendum\Helpers\ParamsExpander;
use stdClass;

/**
 * RelatedAnnotation
 * Shorthand notation:
 *
 * Related(Company\Project\Projects, join = {'_id' = 'entity_id'}, sort = {'_id' = 1}, true)
 *
 * Expanded notation:
 *
 * Related(class = Company\Project\Projects, join = {'_id' => 'entity_id'}, sort = {'_id' = 1}, updatable = true)
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedAnnotation extends MetaAnnotation
{

	public $class;
	public $join;
	public $updatable;
	public $value;

	public function init()
	{
		$relMeta = $this->_getMeta();
		$relMeta->single = true;
		$relMeta->isArray = false;
		$this->getEntity()->related = $relMeta;
		$this->getEntity()->propagateEvents = true;
		$this->getEntity()->owned = true;
	}

	/**
	 *
	 * @return stdClass
	 */
	protected function _getMeta()
	{
		/**
		 * TODO Does not expand params on SimpleTree
		 */
		$data = ParamsExpander::expand($this, ['class', 'join', 'sort', 'updatable']);
		$relMeta = new stdClass();
		foreach ($data as $key => $val)
		{
			$relMeta->$key = $val;
		}
		if (empty($relMeta->class))
		{
			$relMeta->class = $this->getMeta()->type()->name;
		}
		if (empty($relMeta->join))
		{
			$relMeta->join = null;
		}
		if (empty($relMeta->sort))
		{
			$relMeta->sort = [
				'_id' => 1
			];
		}
		return $relMeta;
	}

}
