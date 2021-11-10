<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdminMappingTableCommand extends Command
{
    protected static $defaultName = 'admin:mapping:table';
    protected static $defaultDescription = 'Create entity';

    protected function configure()
    {
        $this
            ->setDescription('Créer l\'entity passé en argument.')
            ->addArgument('nameTable', InputArgument::REQUIRED, 'nom de la table (Windev***)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $nameTable = $input->getArgument('nameTable');

        $command = $this->getApplication()->find('do:mapping:import');
        $arguments = [
            'command' => 'do:mapping:import',
            'name' => 'App\Windev',
            'mapping-type' => 'annotation',
            '--path' => 'src/Windev',
            '--filter' => $nameTable,
            '--em' => 'windev'
        ];
        $greetInput = new ArrayInput($arguments);
        try {
            $command->run($greetInput, $output);
        } catch (\Exception $e) {
            $io->error('Erreur run do:ma:import : ' . $e);
        }

        $io->comment('php bin/console make:entity --regenerate App\Windev');
        $io->comment('--- [FIN DE LA COMMANDE] ---');

        return Command::SUCCESS;
    }
}
