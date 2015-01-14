<?php

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
		$this->storage = new AddendumStorage($owner, 1);
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
