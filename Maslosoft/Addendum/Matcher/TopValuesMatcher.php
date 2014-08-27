<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Matcher;

/**
 * TopValuesMatcher
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TopValuesMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new NonArrayMatcher);
		$this->add(new MoreValuesMatcher);
	}


	protected function process($value)
	{
		
		$result = [];
		if(is_array($value))
		{
			foreach ($value as $key => $value)
			{
				if(!is_numeric($key))
				{
					$result[$key] = $value;
				}
			}
			if($result)
			{
				return $result;
			}
			var_dump($value);
		}
		return ['value' => $value];
	}
}
