<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we send out.
|
*/

trait ApiResponder
{
	
	protected function success(Collection $data, ?string $message = null, int $code = 200) : JsonResponse
	{
		return response()->json([
			'status' => true,
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function error(?string $message = null, int $code, ?array $data = []) : JsonResponse
	{
		return response()->json([
			'status' => false,
			'message' => $message,
			'data' => $data
		], $code);
	}

}