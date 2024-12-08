<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use SoapClient;
use Exception;

class CbrService
{
	private const CBR_WSDL_URL = 'https://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL';
	private const CACHE_TTL = 86400;

	public function getRates(string $date): array
	{
		return Cache::remember("cbr_rates_{$date}", self::CACHE_TTL, fn() => $this->fetchRatesFromApi($date));
	}

	public function getExchangeRate(array $rates, string $quoteCurrency, string $baseCurrency = 'RUR'): ?float
	{
		$quoteRate = $this->findRate($rates, $quoteCurrency);
		$baseRate = $baseCurrency === 'RUR' ? 1 : $this->findRate($rates, $baseCurrency);

		return $quoteRate && $baseRate ? $quoteRate / $baseRate : null;
	}

	private function findRate(array $rates, string $currencyCode): ?float
	{
		return collect($rates['ValuteData']['ValuteCursOnDate'] ?? [])
			->firstWhere('VchCode', $currencyCode)['Vcurs'] ?? null;
	}

	private function fetchRatesFromApi(string $date): array
	{
		try {
			$response = (new SoapClient(self::CBR_WSDL_URL))->GetCursOnDate(['On_date' => $date]);
			$xml = simplexml_load_string($response->GetCursOnDateResult->any);

			if ($xml === false) {
				throw new Exception('Ошибка парсинга XML.');
			}

			return json_decode(json_encode($xml), true);
		} catch (Exception $e) {
			Log::error("Ошибка при получении курсов валют для {$date}: {$e->getMessage()}");
			throw new Exception('Не удалось получить курсы валют. Попробуйте позже.');
		}
	}
}