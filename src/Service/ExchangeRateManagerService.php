<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Exchange;
use DateTimeImmutable;

class ExchangeRateManagerService
{
    private $entityManager;
    private $exchangeRateClientService;
    private $dateFormat = 'D, d M Y H:i:s e';

    public function __construct(EntityManagerInterface $entityManager, ExchangeRateClientService $ExchangeRateClientService)
    {
        $this->exchangeRateClientService = $ExchangeRateClientService;
        $this->entityManager = $entityManager;
    }

    /**
     * getAvailableCurrencies
     *
     * @return array
     */
    public function getAvailableCurrencies(): array
    {

        return $this->exchangeRateClientService->fetchAvailableCurrencies();
    }

    /**
     * updateExchangeRate
     *
     * @param  string $currency
     * @return bool
     */
    public function updateExchangeRates(): bool
    {
        $availableCurrencies = $this->getAvailableCurrencies();

        foreach ($availableCurrencies as $currency) {
            $this->updateExchangeRate($currency);
        }

        return true;
    }

    /**
     * updateExchangeRate
     *
     * @param  string $currency
     * @return bool
     */
    public function updateExchangeRate(string $currency): bool
    {
        // Get live Currency exchangerate data
        $exchangeRates = $this->exchangeRateClientService->fetchForCurrency($currency);

        // Write result
        foreach ($exchangeRates as $data) {
            $existingExchange = $this->entityManager->getRepository(Exchange::class)
                ->findOneBy(['source_currency' => $currency, 'target_currency' => $data['code']]);

            if ($existingExchange instanceof Exchange) {
                // Update existing entity
                $existingExchange->setRate($data['rate']);
                $lastUpdated = DateTimeImmutable::createFromFormat($this->getDateFormat(), $data['date']);
                $existingExchange->setLastUpdated($lastUpdated);
            } else {
                // Create new entity
                $exchange = new Exchange();
                $exchange->setSourceCurrency(strtoupper($currency));
                $exchange->setTargetCurrency($data['code']);
                $exchange->setRate($data['rate']);
                $lastUpdated = DateTimeImmutable::createFromFormat($this->getDateFormat(), $data['date']);
                $exchange->setLastUpdated($lastUpdated);

                $this->entityManager->persist($exchange);
            }
        }

        $this->entityManager->flush();

        return true;
    }

    /**
     * Get the value of dateFormat
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Set the value of dateFormat
     */
    public function setDateFormat(string $dateFormat): self
    {
        $this->dateFormat = $dateFormat;

        return $this;
    }
}
