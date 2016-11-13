<?php

namespace dwalker109\Battle;

use dwalker109\Battler\Brute;
use dwalker109\Battler\Grappler;
use dwalker109\Battler\Swordsman;

class Battle
{
    // Properties to track and display battle state
    public $is_active = true;
    public $messages = [];
    public $player_1;
    public $player_2;
    
    // Turn tracking
    private $max_turns = 30;
    private $current_turn = 1;
    
    // Internal handles to participants
    private $player_current;
    private $player_next;
    
    // Register available battler types
    private $battler_types = [
        Swordsman::class,
        Brute::class,
        Grappler::class,
    ];
    
    /**
     * Create a new battle between the named players.
     *
     * @param string $name_1
     * @param string $name_2
     *
     * @return void
     */
    public function __construct($name_1, $name_2)
    {
        // Create and assign a battler for each player, chosen at random.
        $types = array_rand(array_flip($this->battler_types), 2);
        $players = [
            $player_1 = new $types[0]($name_1, $this),
            $player_2 = new $types[1]($name_2, $this),
        ];

        // Sort by defence, ascending
        usort($players, function($left, $right) {
            return $left->attr()->defence <=> $right->attr()->defence;
        });
        
        // Sort by speed, descending
        usort($players, function($left, $right) {
            return $right->attr()->speed <=> $left->attr()->speed;
        });
        
        // Use the ordered array to set 'pointers' for the players
        $this->player_current = $this->player_1 = array_shift($players);
        $this->player_next = $this->player_2 = array_shift($players);
    }
        
    /**
     * Carry out a single simulation turn.
     *
     * @return void
     */
    public function calculateTurn()
    {
        // Attack, cleanup and rotate
        $this->player_current->attack($this->player_next);
        $this->player_current->initNextTurn();
        $this->rotatePlayers();
        
        // Check/increment current turn
        if ($this->current_turn++ === $this->max_turns) {
            $this->is_active = false;
            $this->pushMessage("{$this->max_turns} turns exceeded, a draw is declared.");
        }
        
    }
    
    /**
     * Update internal turn order reference.
     *
     * @return void
     */
    private function rotatePlayers()
    {
        $pointer = $this->player_current;
        $this->player_current = $this->player_next;
        $this->player_next = $pointer;
    }
    
    /**
     * Add an action message for the current turn.
     *
     * @param string $message
     *
     * @return void
     */
    public function pushMessage(string $message)
    {
        $this->messages[] = $message;
    }
    
    /**
     * Return and clear any turn messages.
     *
     * @return array
     */
    public function popMessages(): array
    {
        $messages = $this->messages;
        $this->messages = [];
        
        return $messages;
    }
}
