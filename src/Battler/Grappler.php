<?php

namespace dwalker109\Battler;

class Grappler extends Battler
{
    // Value constraints used during construction
    protected $property_constraints = [
        "health" => [
            "min" => 60,
            "max" => 100,
        ],
        "strength" => [
            "min" => 75,
            "max" => 80,
        ],
        "defence" => [
            "min" => 35,
            "max" => 40,
        ],
        "speed" => [
            "min" => 60,
            "max" => 80,
        ],
        "luck" => [
            "min" => 0.3,
            "max" => 0.4,
        ],
    ];
}
