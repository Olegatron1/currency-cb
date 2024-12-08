<?php

declare(strict_types=1);

namespace App\Repositories;

use SoapClient;
use Exception;
use Illuminate\Support\Facades\Log;

class CbrRepository
{
	private string $wsdlUrl;

	public function __construct()
	{
		$this->wsdlUrl = config('currency.soap_wsdl_url');
	}

	/**
	 * @throws Exception
	 */
	public function fetchRatesFromApi(string $date): array
	{
		try {
			$response = (new SoapClient($this->wsdlUrl))->GetCursOnDate(['On_date' => $date]);
			$xml = simplexml_load_string($response->GetCursOnDateResult->any);

			if ($xml === false) {
				throw new Exception('Ошибка XML.');
			}

			return json_decode(json_encode($xml), true);
		} catch (Exception $e) {
			Log::error("Ошибка при получении курсов валют для $date: {$e->getMessage()}");
			throw new Exception('Не удалось получить курсы валют. Попробуйте позже.');
		}
	}
}
