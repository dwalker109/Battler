<?php

namespace dwalker109\Battle;

use dwalker109\Battle\Battle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CliBattleCommand extends Command
{
    /**
     * Set up this Symfony command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName("battle:run");
        $this->setDescription("Runs a CLI based battle.");
    }

    /**
     * Run the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Each combatant's name will be requested and stored here
        $names = [
            'first' => null,
            'second' => null,
        ];
        
        // Get each name from CLI and validate the length
        foreach ($names as $label => $name) {
            $helper = $this->getHelper('question');
            $question = new Question("Please enter the name of the {$label} combatant: ");
            $question->setValidator(function ($answer) {
                if (strlen($answer) > 32) {
                    throw new \RuntimeException(
                        'The combatant\'s name must be 32 characters or less.'
                    );
                }
                
                return $answer;
            });
            
            $names[$label] = $helper->ask($input, $output, $question);
        }
        
        // Create the battle and run the game loop
        $battle = new Battle($names['first'], $names['second']);
        
        while ($battle->is_active) {
            $battle->calculateTurn();
            $this->render($battle, $output);
        }
    }
    
    /**
     * Output the current 'frame' of the Battle to the CLI.
     *
     * @param Battle $battle
     * @param OutputInterface $output
     *
     * @return void
     */
    private function render(Battle $battle, OutputInterface $output)
    {
        // Get messages
        $turn_messages = $battle->popMessages();
        
        // // Sort by key (microtime) for display
        // usort($turn_messages, function ($left, $right) {
        //     return $left['microtime'] <=> $right['microtime'];
        // });

        foreach ($turn_messages as $message) {
            $output->writeln($message['text']);
        }
    }
}
