<?php

namespace App\Http\Requests\Common;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
/**
 * FormRequestのバリデート結果をJSONに変換して返却するトレイト
 */
trait ApiFormRequestTrait
{
	protected function failedValidation( Validator $validator )
	{
		$res = [];
		$res['error']  = $validator->errors()->all();
		throw new HttpResponseException(new Response(json_encode($res)), 422);
	}
}
