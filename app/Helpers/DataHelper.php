<?php

namespace App\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class DataHelper
{

	public const NAME_SEPERATE_CHAR = ' ';
	/**
	 * Utils to get all data from $context
	 *
	 * @param mixed $context context of resource
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @param bool $apiUseForApp
	 * @param array $expandFields
	 * @param array $exceptFields
	 *
	 * @return array
	 */
	public static function getDataFromModel($context, Model $model, $apiUseForApp = false, $expandFields = [], $exceptFields = [])
	{
		//
		// Fillable fields
		//
		$fields = $model->getFillable();

		//
		// Add translatable fields
		//
		if ($model->translatedAttributes) {
			array_push($fields, $model->translatedAttributes);
		}

		//
		// Remove hidden field
		//
		$fields = Arr::flatten($fields);
		if ($model->getHidden()) {
			array_push($exceptFields, $model->getHidden());
			foreach ($exceptFields as $hf) {
				// Get index of hidden in fields array
				$filtered = Arr::where($fields, function ($value, $key) use ($hf) {
					return $value == $hf;
				});

				// Remove hidden by index in fields array
				foreach ($filtered as $k => $v) {
					$fields = Arr::except($fields, [$k]);
				}
			}
		}

		//
		// Add expand fields
		//
		array_push($fields, $expandFields);
		$fields = Arr::flatten($fields);

		//
		// Repair for result array
		//
		$result = [];
		foreach ($fields as $f) {
			// Resource are use in which case: App API or web api
			$fieldName = $f;
			if ($apiUseForApp === true) {
				$fieldName = Str::camel($f);
			}
			$result[$fieldName] = self::safeGet($context, $f);
		}

		return $result;
	}

	/**
	 * Make sure get data not throw unset exception
	 *
	 * @param mixed $obj
	 * @param mixed $key
	 * @param string $default
	 * @param mixed $master
	 */
	public static function safeGet($obj, $key, $default = '', $master = null)
	{
		if (!$obj) return $default;

		if ($master == null) {
			if (is_object($obj)) {
				return isset($obj->{$key}) ? $obj->{$key} : $default;
			}
			return isset($obj[$key]) ? $obj[$key] : $default;
		}


		if (is_object($obj)) {
			return isset($obj->{$key}) && isset($master[$obj->{$key}])
				? $master[$obj->{$key}]
				: $default;
		}

		return isset($obj[$key]) && isset($master[$obj[$key]])
			? $master[$obj[$key]]
			: $default;
	}
}
