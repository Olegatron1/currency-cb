<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Contracts\ExchangeRateServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetRateRequest;
use App\Http\Resources\RateResource;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
	public function __construct(
		private readonly ExchangeRateServiceInterface $exchangeRateService
	) {}

	public function getRate(GetRateRequest $request): JsonResponse|RateResource
	{
		$validated = $request->validated();

		$rateData = $this->exchangeRateService->getExchangeRateData($validated['date'], $validated['quote'], $validated['base'] ?? 'RUR');

		if (!$rateData) {
			return response()->json(['error' => 'Currency pair not found'], 404);
		}

		return new RateResource($rateData);
	}
}
