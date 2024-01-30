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
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;

#[AsCommand(
    name: 'whitelist:list',
    description: 'Show a list of whitelist entries',
)]
class WhitelistListCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $whitelistEntries = $this->entityManager->getRepository(WhitelistEntry::class)->findAll();

        $table = new Table($output);
        $table->setHeaders(['ID', 'ip_address']);

        foreach ($whitelistEntries as $whitelistEntry) {
            $table->addRow([$whitelistEntry->getId(), $whitelistEntry->getIpAddress()]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
