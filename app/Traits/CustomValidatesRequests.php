<?php namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Validator;
use Illuminate\Http\Exception\HttpResponseException;

trait CustomValidatesRequests {

	/**
	 * Validate the given request with the given rules , messages and customAttributes.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  array  $rules
	 * @param  array  $messages
	 * @param  array  $customAttributes
	 * @return void
	 */
	public function validateWithCustomAttribute(Request $request, array $rules, array $messages=array(), array $customAttributes=array())
	{
		$validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

		if ($validator->fails())
		{
			$this->throwValidationException($request, $validator);
		}
	}

}
