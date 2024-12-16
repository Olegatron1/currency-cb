<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Contracts\DataProviderInterface;
use App\Services\CbrService;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class CbrServiceTest extends TestCase
{
	private DataProviderInterface $dataProviderMock;
	private CbrService $cbrService;

	protected function setUp(): void
	{
		parent::setUp();

		$this->dataProviderMock = Mockery::mock(DataProviderInterface::class);

		$this->cbrService = new CbrService($this->dataProviderMock);

		Cache::flush();
	}

	public function testGetExchangeRateDataReturnsCorrectData(): void
	{
		// Arrange
		$date = '2024-06-16';
		$quoteCurrency = 'USD';
		$baseCurrency = 'EUR';

		$mockRates = [
			'ValuteData' => [
				'ValuteCursOnDate' => [
					['VchCode' => 'USD', 'Vcurs' => 75.0],
					['VchCode' => 'EUR', 'Vcurs' => 85.0],
				],
			],
		];

		$expectedRate = 75.0 / 85.0;

		$this->dataProviderMock
			->shouldReceive('fetchRates')
			->once()
			->with($date)
			->andReturn($mockRates);

		$result = $this->cbrService->getExchangeRateData($date, $quoteCurrency, $baseCurrency);

		$this->assertNotNull($result);
		$this->assertEquals($date, $result->date);
		$this->assertEquals($quoteCurrency, $result->quote_currency);
		$this->assertEquals($baseCurrency, $result->base_currency);
		$this->assertEquals($expectedRate, $result->rate);
	}

	public function testGetExchangeRateDataReturnsNullWhenRatesAreEmpty()
	{
		$this->dataProviderMock
			->shouldReceive('fetchRates')
			->with('2024-12-16')
			->once()
			->andReturn([]);

		$service = new CbrService($this->dataProviderMock);

		$result = $service->getExchangeRateData('2024-12-16', 'USD');

		$this->assertNull($result);
	}
}