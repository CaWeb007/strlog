<?php

namespace Yandex\Market\Reference\Storage\Field;

use Yandex\Market;

class DateTimeStrict
{
	public static function getParameters()
	{
		return [
			'save_data_modification' => [static::class, 'getSaveModification'],
			'fetch_data_modification' => [static::class, 'getFetchModification'],
			'default_value' => false, // initialize modifiers for sql_mode=STRICT
		];
	}

	public static function getSaveModification()
	{
		return [
			[static::class, 'fill']
		];
	}

	public static function getFetchModification()
	{
		return [
			[static::class, 'resolve'],
		];
	}

	public static function fill($value)
	{
		if (empty($value))
		{
			$value = Market\Data\DateTime::makeDummy();
		}

		return $value;
	}

	public static function resolve($value)
	{
		if (Market\Data\DateTime::isDummy($value))
		{
			$value = null;
		}

		return $value;
	}
}