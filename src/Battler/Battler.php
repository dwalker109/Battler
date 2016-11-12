<?php

namespace dwalker109\Battler;

abstract class Battler
{
    // Basic battler properties
    private $name;
    private $health;
    private $strength;
    private $defence;
    private $speed;
    private $luck;

    // Value constraints used during construction
    protected $property_constraints = [
        "health" => [
            "min" => 0,
            "max" => 100,
        ],
        "strength" => [
            "min" => 0,
            "max" => 100,
        ],
        "defence" => [
            "min" => 0,
            "max" => 100,
        ],
        "speed" => [
            "min" => 0,
            "max" => 100,
        ],
        "luck" => [
            "min" => 0.00,
            "max" => 1.00,
        ],
    ];
    
    /**
     * Initialise a new combatant and randomly generate properties.
     *
     * @param string $name
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
        
        foreach ($this->property_constraints as $property => $constraints) {
            $this->{$property} = property_exists($this, $property)
                ? $this->random($constraints['min'], $constraints['max'])
                : 0;
        }
    }

    /**
     * Return readable property values, with (TODO!) this turn's modifiers applied.
     *
     * @return srdClass
     */
    public function read()
    {
        return (object) [
            "name" => $this->name,
            "health" => $this->health,
            "strength" => $this->strength,
            "defence" => $this->defence,
            "speed" => $this->speed,
            "luck" => $this->luck,
        ];
    }
    
    /**
     * Return a random integer or float within the passed constraints.
     *
     * @param int|float $min
     * @param int|float $max
     *
     * @return int|float
     */
    private function random($min, $max)
    {
        if (is_integer($min) && is_integer($max)) {
            return mt_rand($min, $max);
        }
        
        if (is_float($min) && is_float($max)) {
            return ($min + lcg_value() * (abs($max - $min)));
        }
        
        // Something unusual was passed - return integer 0
        return 0;
    }
}
