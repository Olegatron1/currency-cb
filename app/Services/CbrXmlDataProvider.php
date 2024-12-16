<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\DataProviderInterface;
use App\Repositories\CbrRepository;

class CbrXmlDataProvider implements DataProviderInterface
{
	public function __construct(private readonly CbrRepository $cbrRepository)
	{
	}

	public function fetchRates(string $date): array
	{
		return $this->cbrRepository->fetchRatesFromApi($date);
	}
}