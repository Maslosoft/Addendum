<?php

/**
 * Resolve simple class constant expressions from source without loading PHP files.
 *
 * Supports literals, self/static constant references, parentheses and string
 * concatenation. Deliberately never executes code from the inspected file.
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Reflection\ReflectionFile;
use UnexpectedValueException;

final class RawClassConstantResolver
{
    public static function resolve(ReflectionFile $reflection, string $name)
    {
        $definitions = self::definitions($reflection->getFileName());
        return self::evaluate($name, $definitions, []);
    }

    private static function definitions(string $file): array
    {
        $source = file_get_contents($file);
        if ($source === false)
        {
            throw new UnexpectedValueException("Cannot read PHP source file '$file'");
        }
        $tokens = token_get_all($source);
        $definitions = [];
        $depth = 0;
        $classDepth = null;
        $pendingClass = false;
        $previous = null;
        $count = count($tokens);
        for ($i = 0; $i < $count; $i++)
        {
            $token = $tokens[$i];
            if (is_array($token))
            {
                if (in_array($token[0], [T_CLASS, T_TRAIT, T_INTERFACE], true) && $classDepth === null && $previous !== T_DOUBLE_COLON)
                {
                    $pendingClass = true;
                }
                if ($token[0] === T_CONST && $classDepth !== null && $depth === $classDepth)
                {
                    $statement = [];
                    for ($i++; $i < $count && $tokens[$i] !== ';'; $i++)
                    {
                        $statement[] = $tokens[$i];
                    }
                    self::collect($statement, $definitions);
                    continue;
                }
                if (!in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true))
                {
                    $previous = $token[0];
                }
                continue;
            }
            if ($token === '{')
            {
                $depth++;
                if ($pendingClass)
                {
                    $classDepth = $depth;
                    $pendingClass = false;
                }
            }
            elseif ($token === '}')
            {
                if ($depth === $classDepth)
                {
                    break;
                }
                $depth--;
            }
            $previous = $token;
        }
        return $definitions;
    }

    private static function collect(array $tokens, array &$definitions): void
    {
        $parts = [];
        $current = [];
        $depth = 0;
        foreach ($tokens as $token)
        {
            if ($token === ',' && $depth === 0)
            {
                $parts[] = $current;
                $current = [];
                continue;
            }
            if ($token === '(' || $token === '[')
            {
                $depth++;
            }
            elseif ($token === ')' || $token === ']')
            {
                $depth--;
            }
            $current[] = $token;
        }
        $parts[] = $current;
        foreach ($parts as $part)
        {
            $part = self::significant($part);
            $equal = array_search('=', $part, true);
            // The token before '=' is the name, also for PHP 8.3 typed constants.
            if ($equal > 0 && is_array($part[$equal - 1]) && $part[$equal - 1][0] === T_STRING)
            {
                $definitions[$part[$equal - 1][1]] = array_slice($part, $equal + 1);
            }
        }
    }

    private static function significant(array $tokens): array
    {
        return array_values(array_filter($tokens, static function ($token) {
            return !is_array($token) || !in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true);
        }));
    }

    private static function evaluate(string $name, array $definitions, array $resolving)
    {
        if (!isset($definitions[$name]) || isset($resolving[$name]))
        {
            throw new UnexpectedValueException("Cannot resolve unloaded class constant '$name' (missing or circular reference)");
        }
        $resolving[$name] = true;
        $tokens = $definitions[$name];
        $index = 0;
        $value = self::expression($tokens, $index, $definitions, $resolving);
        if ($index !== count($tokens))
        {
            throw new UnexpectedValueException("Unsupported expression for unloaded class constant '$name'");
        }
        return $value;
    }

    private static function expression(array $tokens, int &$index, array $definitions, array $resolving)
    {
        $value = self::atom($tokens, $index, $definitions, $resolving);
        while (($tokens[$index] ?? null) === '.')
        {
            $index++;
            $value = (string) $value . (string) self::atom($tokens, $index, $definitions, $resolving);
        }
        return $value;
    }

    private static function atom(array $tokens, int &$index, array $definitions, array $resolving)
    {
        $token = $tokens[$index] ?? null;
        if ($token === '(')
        {
            $index++;
            $value = self::expression($tokens, $index, $definitions, $resolving);
            if (($tokens[$index++] ?? null) !== ')')
            {
                throw new UnexpectedValueException('Unbalanced parentheses in unloaded class constant');
            }
            return $value;
        }
        if (is_array($token))
        {
            $index++;
            if ($token[0] === T_CONSTANT_ENCAPSED_STRING)
            {
                $text = $token[1];
                $body = substr($text, 1, -1);
                return $text[0] === "'" ? str_replace(["\\\\", "\\'"], ["\\", "'"], $body) : stripcslashes($body);
            }
            if ($token[0] === T_LNUMBER)
            {
                return intval(str_replace('_', '', $token[1]), 0);
            }
            if ($token[0] === T_DNUMBER)
            {
                return (float) str_replace('_', '', $token[1]);
            }
            if ($token[0] === T_STRING)
            {
                $keyword = strtolower($token[1]);
                if ($keyword === 'true' || $keyword === 'false' || $keyword === 'null')
                {
                    return $keyword === 'null' ? null : ($keyword === 'true');
                }
                if (($keyword === 'self' || $keyword === 'static') && is_array($tokens[$index] ?? null) && $tokens[$index][0] === T_DOUBLE_COLON && is_array($tokens[$index + 1] ?? null) && $tokens[$index + 1][0] === T_STRING)
                {
                    $referenced = $tokens[$index + 1][1];
                    $index += 2;
                    return self::evaluate($referenced, $definitions, $resolving);
                }
            }
        }
        throw new UnexpectedValueException('Unsupported syntax in unloaded class constant expression');
    }
}
