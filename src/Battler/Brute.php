<?php

namespace dwalker109\Battler;

class Brute extends Battler
{
    // Value constraints used during construction
    protected $property_constraints = [
        "health" => [
            "min" => 90,
            "max" => 100,
        ],
        "strength" => [
            "min" => 65,
            "max" => 75,
        ],
        "defence" => [
            "min" => 40,
            "max" => 50,
        ],
        "speed" => [
            "min" => 40,
            "max" => 65,
        ],
        "luck" => [
            "min" => 0.3,
            "max" => 0.35,
        ],
    ];
}
