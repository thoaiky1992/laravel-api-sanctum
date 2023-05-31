<?php

namespace App\Http\Requests;

use App\Http\Requests\Common\ApiFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomRequest extends FormRequest
{
	use ApiFormRequestTrait;

	public function convertRequest(string $requestClass) {
		$request = $requestClass::createFrom($this);

		$app = app();
		$request->setContainer($app)->setRedirector($app->make(Redirector::class));

		$request->prepareForValidation();
		$request->getValidatorInstance();

		return [
			'validator' => $request->getValidatorInstance(),
			'failedValidation' => $this->failedValidation,
			'request' => $request
		];
	}

	// Stop on first validate fail
	// protected $stopOnFirstFailure = true;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 *
	 */
	public function rules()
	{
		return [
		];
	}

	protected function failedValidation( Validator $validator )
	{
		$response['error']  = $validator->errors()->all();

		throw new HttpResponseException(response()->json($response, 422));
	}
}
