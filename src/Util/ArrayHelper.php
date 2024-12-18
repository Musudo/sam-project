<?php

namespace App\Util;

class ArrayHelper
{
	/**
	 * @param $keys | fields to merge
	 * @param $data | array from which you want to get data
	 * @return array
	 * @uses | merges specific fields of one array into another array
	 * and returns new array
	 */
	public static function mergeArraysWithReplacement($keys, $data): array
	{
		$ret = [];
		foreach ($keys as $key) {
			$ret += [$key => ''];
		}
		return array_replace($ret, array_intersect_key($data, array_fill_keys($keys, '')));
	}
	// TODO: make also work with nested objects, so you can for example get id from nested activity object

}