<?php

namespace App\Command;

use App\Entity\WhitelistEntry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'whitelist:add',
    description: 'Add ip address to the whitelist',
)]
class WhitelistAddCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('ip_address', InputArgument::REQUIRED, 'IP address');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $ipAddress = $input->getArgument('ip_address');

        if (!($ipAddress && filter_var($ipAddress, FILTER_VALIDATE_IP))) {
            $io->error('Provide a valid ip address');

            return Command::FAILURE;
        }

        $existingWhitelistEntry = $this->entityManager->getRepository(WhitelistEntry::class)
            ->findOneBy(['ip_address' => $ipAddress]);

        if ($existingWhitelistEntry instanceof WhitelistEntry) {
            $io->warning(sprintf('IP address %s already in the whitelist', $ipAddress));

            return Command::SUCCESS;
        }

        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->setIpAddress($ipAddress);
        $this->entityManager->persist($whitelistEntry);
        $this->entityManager->flush();

        $io->success(sprintf('IP address %s has been added to the whitelist', $ipAddress));

        return Command::SUCCESS;
    }
}
