<?php

namespace App\Helpers;

use Illuminate\Support\Str;

trait UsesUuid
{
	protected static function bootUsesUuid()
	{
		static::creating(function ($model) {
			$model->uuid = (string) Str::uuid();
		});
	}
}