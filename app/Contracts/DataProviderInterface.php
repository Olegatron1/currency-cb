<?php

declare(strict_types=1);

namespace App\Contracts;

interface DataProviderInterface
{
	public function fetchRates(string $date): array;
}