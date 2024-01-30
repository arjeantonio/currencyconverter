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
    name: 'whitelist:delete',
    description: 'Delete an ip address from the whitelist',
)]
class WhitelistDeleteCommand extends Command
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
            ->addArgument('id', InputArgument::REQUIRED, 'Whitelist record id   ');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $id = $input->getArgument('id');

        if (!($id && filter_var($id, FILTER_VALIDATE_INT) !== false)) {
            $io->error('Provide a valid an id number');

            return Command::FAILURE;
        }

        $existingWhitelistEntry = $this->entityManager->getRepository(WhitelistEntry::class)
            ->find($id);

        if (!($existingWhitelistEntry instanceof WhitelistEntry)) {
            $io->warning(sprintf('Whitelist record %s not found', $id));

            return Command::SUCCESS;
        }

        $this->entityManager->remove($existingWhitelistEntry);
        $this->entityManager->flush();

        $io->success(sprintf('Whitelist record %s has been deleted', $id));

        return Command::SUCCESS;
    }
}
