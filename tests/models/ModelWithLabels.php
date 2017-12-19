<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithLabels
 * @Label('Model')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithLabels implements AnnotatedInterface
{

	const Title = 'My Title';
	const TitleLabel = 'Model Title';
	const State = 'Alabama';
	const StateLabel = 'State of residence';

	/**
	 * @Label('Model Title');
	 * @var string
	 */
	public $title = self::Title;

	/**
	 * @Label('State of residence');
	 * @var string
	 */
	public $state = self::State;

}
