<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithoutLabels
 * NOTE: Do not place label annotation anywhere in this model
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithoutLabels implements AnnotatedInterface
{

	const Title = 'Some Title';
	const TitleLabel = 'No label';
	const State = 'Wisconsin';
	const StateLabel = 'State of birth';

	/**
	 * @var string
	 */
	public $title = self::Title;

	/**
	 * @var string
	 */
	public $state = self::State;

}
