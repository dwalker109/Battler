<?php

namespace dwalker109\Battle;

use dwalker109\Combatant\Brute;
use dwalker109\Combatant\Grappler;
use dwalker109\Combatant\Swordsman;

class Battle
{
    private $player_1;
    private $player_2;
    
    // Register available combatant types
    private $combatant_types = [
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
        $types = array_rand(array_flip($this->combatant_types), 2);
        $this->player_1 = new $types[0]($name_1);
        $this->player_2 = new $types[1]($name_1);
    }
}
