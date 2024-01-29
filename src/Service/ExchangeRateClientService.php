<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateClientService
{
    private $httpClient;
    private $baseCurrency = 'EUR';
    private $logger;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function fetchForCurrency(string $currency): array
    {
        $currency = strtolower($currency);
        $url = sprintf('https://www.floatrates.com/daily/%s.json', $currency);
        $this->logger->info('Fetching data for currency {currency} from {url}', [
            'currency' => $currency,
            'url' => $url,
        ]);
        try {
            $response = $this->httpClient->request('GET', $url);
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode <= 299) {
                $this->logger->info('Successfully fetched data for currency {currency}', [
                    'currency' => $currency,
                ]);

                return $response->toArray();
            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to fetch data for currency {currency}: {error}', [
                'currency' => $currency,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
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
