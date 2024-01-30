<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\ExchangeRateFetcher;
use App\Service\ExchangeRateManagerService;
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'exchangerates:update',
    description: 'Manage exchangerates',
)]
class ExchangeRatesUpdateCommand extends Command
{
    private $exchangeRateManagerService;

    public function __construct(ExchangeRateManagerService $exchangeRateManagerService)
    {
        parent::__construct();

        $this->exchangeRateManagerService = $exchangeRateManagerService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('currency', InputArgument::OPTIONAL, 'Currency code')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Update all currencies');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $currencyCode = $input->getArgument('currency');
        $importAll = $input->getOption('all');

        if (!($currencyCode && $this->isValidCurrencyCode($currencyCode)) && !$importAll) {
            $io->error('Provide a 3 letter currency code');

            return Command::FAILURE;
        }

        $currencyCodes = [];
        if ($currencyCode) {
            $currencyCodes[] = $currencyCode;
        }

        if ($importAll) {
            $currencyCodes = $this->exchangeRateManagerService->getAvailableCurrencies();
        }
        $this->updateCurrenciesByCodes($currencyCodes, $output);
        $io->success(sprintf('Rates imported for %s record(s)', count($currencyCodes)));

        return Command::SUCCESS;
    }

    /**
     * updateCurrenciesByCodes
     *
     * @param  array $currencyCodes
     * @param  OutputInterface $output
     * @return bool
     */
    private function updateCurrenciesByCodes(array $currencyCodes, OutputInterface $output): bool
    {
        $progressBar = new ProgressBar($output, count($currencyCodes));
        foreach ($currencyCodes as $currency) {
            $this->exchangeRateManagerService->updateExchangeRate($currency);
            $progressBar->advance();
        }
        $progressBar->finish();

        return true;
    }

    /**
     * isValidCurrencyCode
     *
     * @param  mixed $currencyCode
     * @return bool
     */
    private function isValidCurrencyCode(mixed $value): bool
    {
        return is_string($value) && strlen($value) === 3;
    }
}
