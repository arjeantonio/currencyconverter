<?php

namespace App\Service;

use App\Entity\Exchange;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyConverterService
{
    private $exchangeRepository;

    public function __construct(ExchangeRepository $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
    }

    public function convertValuesForSourceCurrency(string $sourceCurrency, float $value)
    {
        $exchangeRecords = $this->exchangeRepository->findBy(['source_currency' => $sourceCurrency]);

        $convertedValues = [];

        foreach ($exchangeRecords as $exchangeRecord) {
            $convertedValues[] = $this->applyConversion($exchangeRecord, $value);
        }

        return $convertedValues;
    }

    /**
     * applyConversion
     *
     * @param  mixed $exchangeRecord
     * @param  mixed $value
     * @return array
     */
    public function applyConversion(Exchange $exchangeRecord, float $value)
    {
        $convertedValue = $value * $exchangeRecord->getRate();

        return [
            'source_currency' => $exchangeRecord->getSourceCurrency(),
            'target_currency' => $exchangeRecord->getTargetCurrency(),
            'original_value' => $value,
            'converted_value' => $convertedValue,
        ];
    }
}
