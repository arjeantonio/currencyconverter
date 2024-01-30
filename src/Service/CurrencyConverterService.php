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

    /**
     * convertBySourceCurrency
     *
     * @param  string $sourceCurrency
     * @param  float $value
     * @return array
     */
    public function convertBySourceCurrency(string $sourceCurrency, float $value)
    {
        $exchangeRecords = $this->exchangeRepository->findBy(['source_currency' => $sourceCurrency]);

        $convertedValues = [];

        foreach ($exchangeRecords as $exchangeRecord) {
            $convertedValues[] = $this->create(
                $exchangeRecord->getSourceCurrency(),
                $exchangeRecord->getTargetCurrency(),
                $value,
                $this->convert($value, $exchangeRecord->getRate()),
            );
        }

        return $convertedValues;
    }

    /**
     * Create list of return values
     *
     * @param  string $source_currency
     * @param  string $target_currency
     * @param  float $original_value
     * @param  float $converted_value
     * @return array
     */
    public function create($source_currency, $target_currency, $original_value, $converted_value)
    {
        return [
            'source_currency' => $source_currency,
            'target_currency' => $target_currency,
            'original_value' => $original_value,
            'converted_value' => $converted_value,
        ];
    }

    /**
     * convert
     *
     * @param  float $amount
     * @param  float $rate
     * @return float
     */
    public function convert(float $amount, float $rate)
    {
        return $amount * $rate;
    }
}
