<?php

if (!defined('T_NAME_QUALIFIED'))
{
	define('T_NAME_QUALIFIED', 265);
}
// based on original work from the PHP Laravel framework
if (!function_exists('str_contains'))
{
	function str_contains($haystack, $needle): bool
	{
		return $needle !== '' && mb_strpos($haystack, $needle) !== false;
	}
}