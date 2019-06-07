<?php


namespace Maslosoft\Addendum\Cache;


use function get_class;
use function is_object;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Cache\PhpCache\Checker;
use Maslosoft\Addendum\Cache\PhpCache\Cleaner;
use Maslosoft\Addendum\Cache\PhpCache\Reader;
use Maslosoft\Addendum\Cache\PhpCache\Writer;
use Maslosoft\Addendum\Helpers\Cacher;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Cli\Shared\ConfigDetector;
use UnexpectedValueException;

class ClassCache
{
	/**
	 * @var string
	 */
	private static $runtimePath = null;

	/**
	 * @var string
	 */
	private $className = '';

	/**
	 * Root path of storage
	 * @var string
	 */
	private $storagePath = '';

	/**
	 * Base path for current meta class
	 * @var string
	 */
	private $basePath = '';

	/**
	 * @var string
	 */
	private $metaClass = '';

	/**
	 * @var NsCache
	 */
	private $nsCache;

	/**
	 * @var Addendum
	 */
	private $addendum;

	/**
	 * @var Writer
	 */
	private $writer;

	/**
	 * @var Checker
	 */
	private $checker;

	/**
	 * @var Reader
	 */
	private $reader;

	/**
	 * @var Cleaner
	 */
	private $cleaner;

	/**
	 *
	 * @param string                           $metaClass
	 * @param AnnotatedInterface|object|string $component
	 * @param MetaOptions|Addendum             $options
	 */
	public function __construct(string $metaClass, $component, $options = null)
	{
		if (null === self::$runtimePath)
		{
			self::$runtimePath = (new ConfigDetector)->getRuntimePath();
		}
		$this->storagePath = self::$runtimePath . '/addendum';
		$this->metaClass = $metaClass;
		if(is_object($component))
		{
			$component = get_class($component);
		}
		$this->className = $component;
		$this->setOptions($options);

		$this->reader = new Reader($this->basePath);
		$this->writer = new Writer($this->basePath);
		$this->cleaner = new Cleaner($this->basePath);
		$this->checker = new Checker($this->basePath);
	}

	public function get($className)
	{
		assert(!empty($className));
		if($this->addendum->checkMTime)
		{
			if(!$this->checker->isValid($className))
			{
				return false;
			}
		}
		$data = $this->reader->read($className);

		return $data;
	}

	public function set($className, $data): bool
	{
		$this->cleaner->clean($className);
		return $this->writer->write($className, $data);
	}

	public function setOptions(MetaOptions $options = null)
	{
		$instanceId = $this->getInstanceId($options);
		$this->addendum = Addendum::fly($instanceId);
		$this->basePath = sprintf('%s/%s@%s',
			$this->storagePath,
			Cacher::classToFile($this->metaClass),
			$instanceId
		);
		$this->nsCache = new NsCache($this->basePath, $this->addendum);
		$this->nsCache->setOptions($options);
	}

	private function getInstanceId(MetaOptions $options = null)
	{
		if (empty($options))
		{
			$instanceId = Addendum::DefaultInstanceId;
		}
		elseif ($options instanceof Addendum)
		{
			$instanceId = $options->getInstanceId();
		}
		elseif ($options instanceof MetaOptions)
		{
			$instanceId = $options->instanceId;
		}
		else
		{
			throw new UnexpectedValueException('Unknown options');
		}
		return $instanceId;
	}
}