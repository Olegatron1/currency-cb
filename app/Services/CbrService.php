<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ExchangeRateServiceInterface;
use App\Contracts\DataProviderInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CbrService implements ExchangeRateServiceInterface
{
	public function __construct(
		private readonly DataProviderInterface $dataProvider
	) {}

	public function getExchangeRateData(string $date, string $quoteCurrency, string $baseCurrency = 'RUR'): ?object
	{
		$rates = $this->getRates($date);

		if (!$rates) {
			return null;
		}

		$exchangeRate = $this->getExchangeRate($rates, $quoteCurrency, $baseCurrency);

		if (!$exchangeRate) {
			return null;
		}

		return (object) [
			'date' => $date,
			'quote_currency' => $quoteCurrency,
			'base_currency' => $baseCurrency,
			'rate' => $exchangeRate,
		];
	}

	private function getRates(string $date): array
	{
		$cacheTTL = Config::get('currency.cache_ttl', 86400);

		return Cache::remember(
			"cbr_rates_$date",
			$cacheTTL,
			fn() => $this->dataProvider->fetchRates($date)
		);
	}

	private function getExchangeRate(array $rates, string $quoteCurrency, string $baseCurrency = 'RUR'): ?float
	{
		$quoteRate = $this->findRate($rates, $quoteCurrency);
		$baseRate = $baseCurrency === 'RUR' ? 1 : $this->findRate($rates, $baseCurrency);

		return $quoteRate && $baseRate ? $quoteRate / $baseRate : null;
	}

	private function findRate(array $rates, string $currencyCode): ?float
	{
		$rate = collect($rates['ValuteData']['ValuteCursOnDate'] ?? [])
			->firstWhere('VchCode', $currencyCode)['Vcurs'] ?? null;

		return $rate !== null ? (float)$rate : null;
	}
}