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

namespace Maslosoft\Addendum\Builder;

use InvalidArgumentException;
use function assert;
use function get_class;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionFile;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Addendum\Utilities\NameNormalizer;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use function is_array;
use const T_ABSTRACT;
use const T_ARRAY;
use const T_CLASS;
use const T_INTERFACE;
use const T_NAME_QUALIFIED;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;
use const T_STRING;
use const T_TRAIT;

class DocComment
{
	public const TypeTrait = 'trait';
	public const TypeClass = 'class';
	public const TypeInterface = 'interface';
	private static array $use = [];
	private static array $useAliases = [];
	private static array $namespaces = [];
	private static array $types = [];
	private static array $classNames = [];
	private static array $classes = [];
	private static array $methods = [];
	private static array $fields = [];
	private static array $parsedFiles = [];

	public static function clearCache(): void
	{
		self::$namespaces = [];
		self::$types = [];
		self::$classNames = [];
		self::$classes = [];
		self::$methods = [];
		self::$fields = [];
		self::$parsedFiles = [];
	}

	public function get($reflection)
	{
		if ($reflection instanceof ReflectionClass)
		{
			return $this->forClass($reflection);
		}

		if ($reflection instanceof ReflectionMethod)
		{
			return $this->forMethod($reflection);
		}

		if ($reflection instanceof ReflectionProperty)
		{
			return $this->forProperty($reflection);
		}
		throw new InvalidArgumentException("This method can only be used on reflection classes");
	}

	/**
	 * Get doc comment for file
	 * If file contains several classes, $className will be returned
	 * If file name matches class name, this class will be returned
	 * @param string $name
	 * @param string $className
	 * @return array
	 */
	public function forFile(string $name, string $className = ''): array
	{
		$fqn = $this->process($name, $className);
		if ($className !== '')
		{
			$fqn = $className;
		}
		return $this->getResultFor($fqn);
	}

	public function forClass(Reflector $reflection)
	{
		assert($reflection instanceof ReflectionClass || $reflection instanceof ReflectionFile, sprintf("Expected `%s` or `%s`, got `%s`", ReflectionClass::class, ReflectionFile::class, get_class($reflection)));

		$fqn = $reflection->getName();
		$this->process($reflection->getFileName(), $fqn);
		if (ClassChecker::isAnonymous($reflection->name))
		{
			$info = new ReflectionClass($reflection->getName());
			$anonFqn = $reflection->getName();
			NameNormalizer::normalize($anonFqn);
			$this->processAnonymous($info, $anonFqn);
		}
		$result = $this->getResultFor($fqn);
		if (ClassChecker::isAnonymous($reflection->name))
		{
			assert(!empty($anonFqn));
			$result['className'] = self::$classNames[$anonFqn];
			$result['class'] = self::$classes[$anonFqn];
		}
		return $result;
	}

	public function forMethod(Reflector $reflection)
	{
		assert($reflection instanceof ReflectionMethod);
		$this->process($reflection->getDeclaringClass()->getFileName());



		$class = $reflection->getDeclaringClass()->getName();
		$method = $reflection->getName();
		return self::$methods[$class][$method] ?? false;
	}

	public function forProperty(Reflector $reflection)
	{
		assert($reflection instanceof ReflectionProperty);
		$this->process($reflection->getDeclaringClass()->getFileName());


		$class = $reflection->getDeclaringClass()->getName();
		$field = $reflection->getName();
		return self::$fields[$class][$field] ?? false;
	}

	private function processAnonymous(Reflector $reflection, string $fqn): void
	{
		if (!isset(self::$parsedFiles[$fqn]))
		{
			/* @var $reflection ReflectionAnnotatedClass */
			self::$classNames[$fqn] = $fqn;
			self::$classes[$fqn] = $reflection->getDocComment();
			self::$methods[$fqn] = [];
			self::$fields[$fqn] = [];
			foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
			{
				self::$methods[$fqn][$method->name] = $method->getDocComment();
			}
			foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
			{
				self::$fields[$fqn][$property->name] = $property->getDocComment();
			}
			self::$parsedFiles[$fqn] = $fqn;
		}
	}

	private function process(string $file, string $fqn = ''): string
	{
		if (!isset(self::$parsedFiles[$file]))
		{
			$fqn = $this->parse($file, $fqn);
			self::$parsedFiles[$file] = $fqn;
		}
		return self::$parsedFiles[$file];
	}

	protected function parse(string $file, string $fqn = ''): string
	{
		$use = [];
		$aliases = [];
		$namespace = '\\';
		$tokens = $this->getTokens($file);
		$class = false;
		$comment = null;
		$max = count($tokens);
		$i = 0;
		while ($i < $max)
		{
			$token = $tokens[$i];
			if (is_array($token))
			{
				[$code, $value] = $token;

				switch ($code)
				{
					case T_DOC_COMMENT:
						$comment = $value;
						break;

					case T_NAMESPACE:
						$comment = false;
						$tokensCount = count($tokens);
						for ($j = $i + 1; $j < $tokensCount; $j++)
						{
							if ($tokens[$j][0] === T_STRING || $tokens[$j][0] === T_NAME_QUALIFIED)
							{
								$namespace .= '\\' . $tokens[$j][1];
							}
							elseif ($tokens[$j] === '{' || $tokens[$j] === ';')
							{
								break;
							}
						}

						$namespace = preg_replace('~^\\\\+~', '', $namespace);
						break;
					case T_USE:
						// After class declaration, this should ignore `use` trait
						if ($class)
						{
							break;
						}
						$comment = false;
						$useNs = '';
						$tokensCount = count($tokens);
						$as = false;
						for ($j = $i + 1; $j < $tokensCount; $j++)
						{
							$tokenName = $tokens[$j][0];

							if (($tokenName === T_STRING || $tokenName === T_NAME_QUALIFIED) && !$as)
							{
								$useNs .= '\\' . $tokens[$j][1];
							}
							if (($tokenName === T_STRING || $tokenName === T_NAME_QUALIFIED) && $as)
							{
								$alias = $tokens[$j][1];
								break;
							}
							if ($tokenName === T_AS)
							{
								$as = true;
							}
							if ($tokens[$j] === '{' || $tokens[$j] === ';')
							{
								break;
							}
						}
						$use[] = preg_replace('~^\\\\+~', '', $useNs);
						if ($as)
						{
							assert(!empty($alias));
							$aliases[$useNs] = $alias;
						}
						break;
					case T_TRAIT:
					case T_CLASS:
					case T_INTERFACE:
						// Ignore magic constant `::class`
						if ($tokens[$i - 1][0] === T_DOUBLE_COLON)
						{
							break;
						}
						$class = $this->getString($tokens, $i, $max);
						if (empty($fqn))
						{
							$fqn = sprintf('%s\%s', $namespace, $class);
						}
						if ($comment !== false)
						{
							self::$classes[$fqn] = $comment;
							$comment = false;
						}
						self::$namespaces[$fqn] = $namespace;
						self::$classNames[$fqn] = $class;
						self::$use[$fqn] = $use;
						self::$useAliases[$fqn] = $aliases;

						if($code === T_TRAIT)
						{
							self::$types[$fqn] = self::TypeTrait;
						}
						elseif($code === T_CLASS)
						{
							self::$types[$fqn] = self::TypeClass;
						}
						elseif($code === T_INTERFACE)
						{
							self::$types[$fqn] = self::TypeInterface;
						}

						break;

					case T_VARIABLE:
						if ($comment !== false && $class)
						{
							$field = substr($token[1], 1);
							self::$fields[$fqn][$field] = $comment;
							$comment = false;
						}
						break;

					case T_FUNCTION:
						if ($comment !== false && $class)
						{
							$function = $this->getString($tokens, $i, $max);
							self::$methods[$fqn][$function] = $comment;
							$comment = false;
						}

						break;

					// ignore
					case T_WHITESPACE:
					case T_PUBLIC:
					case T_PROTECTED:
					case T_PRIVATE:
					case T_ABSTRACT:
					case T_FINAL:
					case T_VAR:
						break;

					// Might be scalar typed property, eg:
					// `public string $title;`
					case T_STRING:
					case T_ARRAY:
						// NOTE: Maybe it will be better to check ahead, ie if T_STRING, then check it next value is variable

						// Skip whitespace and check token before T_STRING and T_WHITESPACE
						if(isset($tokens[$i - 2]) && is_array($tokens[$i - 2]))
						{
							$prevType = $tokens[$i - 2][0];
							switch ($prevType)
							{
								case T_PUBLIC:
								case T_PROTECTED:
								case T_PRIVATE:
								case T_ABSTRACT:
									break 2;
							}

						}
						// Optional typed parameter, the `?` is present as a *string* token,
						// ie contains only `?` string, not an array
						// Skip whitespace and check token before T_STRING and T_WHITESPACE
						if(isset($tokens[$i - 3]) && is_array($tokens[$i - 3]))
						{
							$prevType = $tokens[$i - 3][0];
							switch ($prevType)
							{
								case T_PUBLIC:
								case T_PROTECTED:
								case T_PRIVATE:
								case T_ABSTRACT:
									break 2;
							}

						}
						break;
					default:
						$comment = false;
						break;
				}
			}
			else
			{
				switch ($token)
				{
					// Don't reset comment tag on `?` token, as it *probably* means optional val
					case '?':
						break;
					default:
						$comment = false;
				}
			}
			$i++;
		}
		return $fqn;
	}

	private function getString($tokens, &$i, $max)
	{
		do
		{
			/**
			 * TODO Workaround for problem described near T_CLASS token
			 */
			if (!isset($tokens[$i]))
			{
				$i++;
				continue;
			}
			$token = $tokens[$i];
			$i++;
			if (is_array($token) && $token[0] === T_STRING)
			{
				return $token[1];
			}
		}
		while ($i <= $max);
		return false;
	}

	private function getTokens(string $file): array
	{
		return token_get_all(file_get_contents($file));
	}

	private function getResultFor(string $fqn): array
	{
		return [
			'namespace' => self::$namespaces[$fqn] ?? [],
			'type' => self::$types[$fqn] ?? '',
			'use' => self::$use[$fqn] ?? [],
			'useAliases' => self::$useAliases[$fqn] ?? [],
			'className' => self::$classNames[$fqn] ?? [],
			'class' => self::$classes[$fqn] ?? '',
			'methods' => self::$methods[$fqn] ?? [],
			'fields' => self::$fields[$fqn] ?? []
		];
	}

}
