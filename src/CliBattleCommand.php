<?php

namespace dwalker109;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CliBattleCommand extends Command
{
    protected function configure()
    {
        $this->setName("battle:run");
        $this->setDescription("Runs a CLI based battle.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
