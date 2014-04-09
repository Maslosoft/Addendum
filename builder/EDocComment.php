<?php

class EDocComment
{
	private static $namespaces = array();
	private static $classNames = array();
	private static $classes = array();
	private static $methods = array();
	private static $fields = array();
	private static $parsedFiles = array();

	public static function clearCache()
	{
		self::$classes = array();
		self::$methods = array();
		self::$fields = array();
		self::$parsedFiles = array();
	}

	public function get($reflection)
	{
		if($reflection instanceof ReflectionClass)
		{
			return $this->forClass($reflection);
		}
		elseif($reflection instanceof ReflectionMethod)
		{
			return $this->forMethod($reflection);
		}
		elseif($reflection instanceof ReflectionProperty)
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
		if(null === $className)
		{
			$className = basename($name, '.' . pathinfo($name, PATHINFO_EXTENSION));
		}
		
		$this->process($name);
		$result = [
			'namespace' => isset(self::$namespaces[$className]) ? self::$namespaces[$className] : [],
			'className' => isset(self::$classNames[$className]) ? self::$classNames[$className] : [],
			'class' => isset(self::$classes[$className]) ? self::$classes[$className] : '',
			'methods' => isset(self::$methods[$className]) ? self::$methods[$className] : [],
			'fields' => isset(self::$fields[$className]) ? self::$fields[$className] : []
		];
		return $result;
	}

	public function forClass($reflection)
	{
		$this->process($reflection->getFileName());
		$name = $reflection->getName();
		return isset(self::$classes[$name]) ? self::$classes[$name] : false;
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
		if(!isset(self::$parsedFiles[$file]))
		{
			$this->parse($file);
			self::$parsedFiles[$file] = true;
		}
	}

	protected function parse($file)
	{
		$namespace = '\\';
		$tokens = $this->getTokens($file);
		$currentClass = false;
		$currentBlock = false;
		$max = count($tokens);
		$i = 0;
		while($i < $max)
		{
			$token = $tokens[$i];
			if(is_array($token))
			{
				list($code, $value) = $token;
				switch($code)
				{
					case T_DOC_COMMENT:
						$comment = $value;
						break;

					case T_NAMESPACE:
						$comment = false;
						for ($j = $i + 1; $j < count($tokens); $j++)
						{
							if ($tokens[$j][0] === T_STRING)
							{
								$namespace .= '\\' . $tokens[$j][1];
							}
							else if ($tokens[$j] === '{' || $tokens[$j] === ';')
							{
								break;
							}
						}
						$namespace = preg_replace('~^\\\\+~', '', $namespace);
						break;

					case T_CLASS:
						$class = $this->getString($tokens, $i, $max);
						if($comment !== false)
						{
							self::$classes[$class] = $comment;
							$comment = false;
						}
						self::$classNames[$class] = $class;
						self::$namespaces[$class] = $namespace;
						break;

					case T_TRAIT:
						$class = $this->getString($tokens, $i, $max);
						if($comment !== false)
						{
							self::$classes[$class] = $comment;
							$comment = false;
						}
						self::$classNames[$class] = $class;
						self::$namespaces[$class] = $namespace;
						break;

					case T_VARIABLE:
						if($comment !== false)
						{
							$field = substr($token[1], 1);
							self::$fields[$class][$field] = $comment;
							$comment = false;
						}
						break;

					case T_FUNCTION:
						if($comment !== false)
						{
							$function = $this->getString($tokens, $i, $max);
							self::$methods[$class][$function] = $comment;
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
	}

	private function getString($tokens, &$i, $max)
	{
		do
		{
			$token = $tokens[$i];
			$i++;
			if(is_array($token))
			{
				if($token[0] == T_STRING)
				{
					return $token[1];
				}
			}
		} while($i <= $max);
		return false;
	}

	private function getTokens($file)
	{
		return token_get_all(file_get_contents($file));
	}
}
