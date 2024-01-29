<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateClientService
{
    private $httpClient;
    private $baseCurrency = 'EUR';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchForCurrency(string $currency): array
    {
        $currency = strtolower($currency);
        $url = sprintf('https://www.floatrates.com/daily/%s.json', $currency);
        $response = $this->httpClient->request('GET', $url);

        return $response->toArray();
    }
    
    /**
     * fetchAvailableCurrencies
     *
     * @return array
     */
    public function fetchAvailableCurrencies(): array
    {
        $response = $this->fetchForCurrency($this->getBasecurrency());

        return array_keys($response);
    }

    /**
     * Get the value of basecurrency
     */
    public function getBasecurrency()
    {
        return $this->baseCurrency;
    }

    /**
     * Set the value of basecurrency
     */
    public function setBasecurrency(string $baseCurrency): self
    {
        $this->baseCurrency = $baseCurrency;

        return $this;
    }
}
