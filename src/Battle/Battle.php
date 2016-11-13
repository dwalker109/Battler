<?php

namespace dwalker109\Battle;

use dwalker109\Battler\Brute;
use dwalker109\Battler\Grappler;
use dwalker109\Battler\Swordsman;

class Battle
{
    // Manage battle state
    public $is_active = true;
    
    // Handles to participants
    public $player_current;
    public $player_opponent;
    
    // Turn and message tracking
    private $max_turns = 30;
    private $current_turn = 1;
    private $messages = [];
    
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
        usort($players, function ($left, $right) {
            return $left->attr()->defence <=> $right->attr()->defence;
        });
        
        // Sort by speed, descending
        usort($players, function ($left, $right) {
            return $right->attr()->speed <=> $left->attr()->speed;
        });
        
        // Use the ordered array to set 'pointers' for the players
        $this->player_current = array_shift($players);
        $this->player_opponent = array_shift($players);
    }
        
    /**
     * Carry out a single simulation turn.
     *
     * @return void
     */
    public function calculateTurn()
    {
        // Run pre skills, attack, run post skills, init next turn, rotate
        $this->player_current->preTurnSkills();
        $this->player_current->attack($this->player_opponent);
        $this->player_current->postTurnSkills();
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
        $this->player_current = $this->player_opponent;
        $this->player_opponent = $pointer;
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
