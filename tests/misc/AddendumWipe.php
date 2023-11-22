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

use Codeception\Event\TestEvent;
use Codeception\Extension;
use Maslosoft\Addendum\Addendum;

/**
 * AddendumWipe
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AddendumWipe extends Extension
{

	// list events to listen to
	public static array $events = [
		'test.before' => 'testBefore',
	];

	public function testBefore(TestEvent $e): void
	{
		// Clear cache if env var is not set or is set to true
		if (false === getenv('ADDENDUM_CACHE_CLEAR') || getenv('ADDENDUM_CACHE_CLEAR'))
		{
			Addendum::cacheClear();
			exec('rm -rf vendor/addendum/*');
		}
	}

}
