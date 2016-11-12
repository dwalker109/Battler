<?php

namespace dwalker109\Battle;

use dwalker109\Battler\Brute;
use dwalker109\Battler\Grappler;
use dwalker109\Battler\Swordsman;

class Battle
{
    // Properties to track battle state
    public $is_active = true;
    private $player_current;
    private $player_next;
    
    // Handles to participants
    public $player_1;
    public $player_2;
    
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
        $this->player_1 = new $types[0]($name_1, $this);
        $this->player_2 = new $types[1]($name_2, $this);

        // To decide who attacks first, usort the players based on their props
        $order = [
            $this->player_1,
            $this->player_2,
        ];

        // Sort by defence, ascending
        usort($order, function($left, $right) {
            return $left->read()->defence <=> $right->read()->defence;
        });
        
        // Sort by speed, descending
        usort($order, function($left, $right) {
            return $right->read()->speed <=> $left->read()->speed;
        });
        
        // Shift off the array to set the initial current and next properties
        $this->player_current = array_shift($order);
        $this->player_next = array_shift($order);
    }
        
    /**
     * Carry out a single simulation turn.
     *
     * @return void
     */
    public function calculateTurn()
    {
        $this->player_current->attack($this->player_next);
        
        foreach(["player_1", "player_2"] as $player) {
            if ($this->{$player}->read()->health <= 0) {
                $this->is_active = false;
            }
        }
        
        $this->rotatePlayers();
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
}
