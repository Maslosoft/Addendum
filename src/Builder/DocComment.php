<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Builder;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class DocComment
{

	private static $use = [];
	private static $useAliases = [];
	private static $namespaces = [];
	private static $classNames = [];
	private static $classes = [];
	private static $methods = [];
	private static $fields = [];
	private static $parsedFiles = [];

	public static function clearCache()
	{
		self::$namespaces = [];
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
		elseif ($reflection instanceof ReflectionMethod)
		{
			return $this->forMethod($reflection);
		}
		elseif ($reflection instanceof ReflectionProperty)
		{
			return $this->forProperty($reflection);
		}
	}

	/**
	 * Get doc comment for file
	 * If file contains several classes, $className will be returned
	 * If file name matches class name, this class will be returned
	 * @param string $name
	 * @param string $className
	 */
	public function forFile($name, $className = null)
	{
		$fqn = $this->process($name);
		if (null !== $className)
		{
			$fqn = $className;
		}
		$result = [
			'namespace' => isset(self::$namespaces[$fqn]) ? self::$namespaces[$fqn] : [],
			'use' => isset(self::$use[$fqn]) ? self::$use[$fqn] : [],
			'useAliases' => isset(self::$useAliases[$fqn]) ? self::$useAliases[$fqn] : [],
			'className' => isset(self::$classNames[$fqn]) ? self::$classNames[$fqn] : [],
			'class' => isset(self::$classes[$fqn]) ? self::$classes[$fqn] : '',
			'methods' => isset(self::$methods[$fqn]) ? self::$methods[$fqn] : [],
			'fields' => isset(self::$fields[$fqn]) ? self::$fields[$fqn] : []
		];

		return $result;
	}

	public function forClass($reflection)
	{
		$this->process($reflection->getFileName());
		$fqn = $reflection->getName();
		$result = [
			'namespace' => isset(self::$namespaces[$fqn]) ? self::$namespaces[$fqn] : [],
			'use' => isset(self::$use[$fqn]) ? self::$use[$fqn] : [],
			'useAliases' => isset(self::$useAliases[$fqn]) ? self::$useAliases[$fqn] : [],
			'className' => isset(self::$classNames[$fqn]) ? self::$classNames[$fqn] : [],
			'class' => isset(self::$classes[$fqn]) ? self::$classes[$fqn] : '',
			'methods' => isset(self::$methods[$fqn]) ? self::$methods[$fqn] : [],
			'fields' => isset(self::$fields[$fqn]) ? self::$fields[$fqn] : []
		];
		return $result;
	}

	public function forMethod($reflection)
	{
		$this->process($reflection->getDeclaringClass()->getFileName());
		$class = $reflection->getDeclaringClass()->getName();
		$method = $reflection->getName();
		return isset(self::$methods[$class][$method]) ? self::$methods[$class][$method] : false;
	}

	public function forProperty($reflection)
	{
		$this->process($reflection->getDeclaringClass()->getFileName());
		$class = $reflection->getDeclaringClass()->getName();
		$field = $reflection->getName();
		return isset(self::$fields[$class][$field]) ? self::$fields[$class][$field] : false;
	}

	private function process($file)
	{
		if (!isset(self::$parsedFiles[$file]))
		{
			$fqn = $this->parse($file);
			self::$parsedFiles[$file] = $fqn;
		}
		return self::$parsedFiles[$file];
	}

	protected function parse($file)
	{
		$use = [];
		$aliases = [];
		$namespace = '\\';
		$tokens = $this->getTokens($file);
		$class = false;
		$fqn = false;
		$comment = null;
		$max = count($tokens);
		$i = 0;
		while ($i < $max)
		{
			$token = $tokens[$i];
			if (is_array($token))
			{
				list($code, $value) = $token;
//				$tokenName = token_name($code);

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
							if ($tokens[$j][0] === T_STRING)
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
							if (isset($tokens[$j][1]) && $tokens[$j][1] == 'IMatcher')
							{
								echo 's';
							}
							if ($tokenName === T_STRING && !$as)
							{
								$useNs .= '\\' . $tokens[$j][1];
							}
							if ($tokenName === T_STRING && $as)
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
							$aliases[$useNs] = $alias;
						}
						break;
					case T_TRAIT:
					case T_CLASS:
					case T_INTERFACE:
						// Ignore magic constant `::class`
						if ($tokens[$i - 1][0] == T_DOUBLE_COLON)
						{
							break;
						}
						$class = $this->getString($tokens, $i, $max);
						if ($comment !== false)
						{
							self::$classes[$class] = $comment;
							$comment = false;
						}
						$fqn = sprintf('%s\%s', $namespace, $class);
						self::$namespaces[$fqn] = $namespace;
						self::$classNames[$fqn] = $class;
						self::$use[$fqn] = $use;
						self::$useAliases[$fqn] = $aliases;
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

					default:
						$comment = false;
						break;
				}
			}
			else
			{
				$comment = false;
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
			 * TODO Workaround for problem desribed near T_CLASS token
			 */
			if (!isset($tokens[$i]))
			{
				$i++;
				continue;
			}
			$token = $tokens[$i];
			$i++;
			if (is_array($token))
			{
				if ($token[0] == T_STRING)
				{
					return $token[1];
				}
			}
		}
		while ($i <= $max);
		return false;
	}

	private function getTokens($file)
	{
		return token_get_all(file_get_contents($file));
	}

}
