<?php

namespace ManagementBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeProjectCommand extends Command
{
    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('manage:initialize-project')
        // the short description shown while running "php bin/console list"
        ->setDescription('Initializes the project. Intended for first-time installation.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        print_r(PHP_EOL);
        print_r('Project Initialized!');
        print_r(PHP_EOL);
    }
}