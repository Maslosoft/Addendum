<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Codeception\Event\TestEvent;
use Codeception\Platform\Extension;
use Maslosoft\Addendum\Addendum;

/**
 * AddendumWipe
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AddendumWipe extends Extension
{

	// list events to listen to
	public static $events = [
		'test.before' => 'testBefore',
	];

	public function testBefore(TestEvent $e)
	{
		// Clear cache if env var is not set or is set to true
		if (false === getenv('ADDENDUM_CACHE_CLEAR') || getenv('ADDENDUM_CACHE_CLEAR'))
		{
			Addendum::cacheClear();
			exec('rm -rf vendor/addendum/*');
		}
	}

}
