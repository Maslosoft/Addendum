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

namespace Maslosoft\Addendum\Cache;

use Maslosoft\Addendum\Addendum;
use Maslosoft\EmbeDi\StoragePersister;

class PhpCache
{

	/**
	 *
	 * @var AddendumStorage
	 */
	private $storage = null;

	/**
	 *
	 * @var StoragePersister
	 */
	private $persister = null;

	private function __construct(Addendum $addendum, $owner)
	{
		$this->storage = new AddendumStorage($owner, Addendum::DefaultInstanceId);
		$this->persister = new StoragePersister($this->storage, $addendum->runtimePath);
		$this->persister->load();
		register_shutdown_function([$this->persister, 'save']);
	}

	public function get($key)
	{
		if(isset($this->storage->meta->$key))
		{
			return $this->storage->meta->$key;
		}
		return false;
	}

	public function set($key, $meta)
	{
		$this->storage->meta->$key = $meta;
	}

	public function flush()
	{
		
	}

}
