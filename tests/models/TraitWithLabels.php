<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

/**
 * ModelWithLabels
 * @Label('Model')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait TraitWithLabels
{

	/**
	 * @Label('Model Title');
	 * @var string
	 */
	public $title = 'Title';

	/**
	 * @Label('State of residence');
	 * @var string
	 */
	public $state = 'State';

}
