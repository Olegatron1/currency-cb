<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetRateRequest;
use App\Http\Resources\RateResource;
use App\Services\CbrService;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
	public function __construct(private readonly CbrService $cbrService)
	{
	}

	public function getRate(GetRateRequest $request): JsonResponse|RateResource
	{
		$validated = $request->validated();

		$rateData = $this->cbrService->getExchangeRateData($validated['date'], $validated['quote'], $validated['base'] ?? 'RUR');

		if (!$rateData) {
			return response()->json(['error' => 'Currency pair not found'], 404);
		}

		return new RateResource($rateData);
	}
}
