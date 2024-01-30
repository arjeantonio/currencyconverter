<?php

namespace App\Tests;

use App\Repository\ExchangeRepository;
use App\Service\CurrencyConverterService;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase
{
    public function testCurrencyConverter(): void
    {
        // Instantiate a mock HttpClientInterface and LoggerInterface
        $exchangeRepository = $this->createMock(ExchangeRepository::class);

        $currencyConverterService = new CurrencyConverterService($exchangeRepository);


        $this->assertEquals(1, $currencyConverterService->convert(1, 1));
        $this->assertEquals(2, $currencyConverterService->convert(1, 2));
        $this->assertEquals(1.5129, $currencyConverterService->convert(1.23, 1.23));
        $this->assertEqualsWithDelta(1.333829482236, $currencyConverterService->convert(1.23, 1.0844142132), 0.00000000000001);
        $this->assertEqualsWithDelta(18605.2938844385637711444, $currencyConverterService->convert(17156.999288617, 1.0844142132), 0.00000000000001);
    }
}
