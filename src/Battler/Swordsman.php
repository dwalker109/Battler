<?php

namespace dwalker109\Battler;

class Swordsman extends Battler
{
    // Value constraints used during construction
    protected $property_constraints = [
        "health" => [
            "min" => 40,
            "max" => 60,
        ],
        "strength" => [
            "min" => 60,
            "max" => 70,
        ],
        "defence" => [
            "min" => 20,
            "max" => 30,
        ],
        "speed" => [
            "min" => 90,
            "max" => 100,
        ],
        "luck" => [
            "min" => 0.3,
            "max" => 0.5,
        ],
    ];
}
