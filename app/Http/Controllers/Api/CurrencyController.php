<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetRateRequest;
use App\Http\Resources\RateResource;
use App\Services\CbrService;

class CurrencyController extends Controller
{
	private CbrService $cbrService;

	public function __construct(CbrService $cbrService)
	{
		$this->cbrService = $cbrService;
	}

	public function getRate(GetRateRequest $request): \Illuminate\Http\JsonResponse|RateResource
	{
		$validated = $request->validated();
		$rates = $this->cbrService->getRates($validated['date']);

		if (!$rates) {
			return response()->json(['error' => 'Unable to fetch rates'], 500);
		}

		$exchangeRate = $this->cbrService->getExchangeRate($rates, $validated['quote'], $validated['base'] ?? 'RUR');

		if (!$exchangeRate) {
			return response()->json(['error' => 'Currency pair not found'], 404);
		}

		// Преобразуем массив в объект
		$rateData = (object) [
			'date' => $validated['date'],
			'quote_currency' => $validated['quote'],
			'base_currency' => $validated['base'] ?? 'RUR',
			'rate' => $exchangeRate,
		];

		return new RateResource($rateData);
	}
}
