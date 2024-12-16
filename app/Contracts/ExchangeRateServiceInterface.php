<?php

declare(strict_types=1);

namespace App\Contracts;

interface ExchangeRateServiceInterface
{
	public function getExchangeRateData(string $date, string $quoteCurrency, string $baseCurrency = 'RUR'): ?object;
}