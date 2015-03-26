<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Gazebo\ConfigContainer;

/**
 * Plugins container
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AddendumPlugins extends ConfigContainer
{

	/**
	 * Matcher plugins
	 * @var string[]|mixed[]
	 */
	public $matcher = [];

}
