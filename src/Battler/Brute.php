<?php

namespace dwalker109\Battler;

use dwalker109\Skills\StunningBlow;

class Brute extends Battler
{
    // Attribute definitions
    protected $definitions = [
        'health' => ['min' => 90, 'max' => 100],
        'strength' => ['min' => 65, 'max' => 75],
        'defence' => ['min' => 40, 'max' => 50],
        'speed' => ['min' => 40, 'max' => 65],
        'luck' => ['min' => 0.3, 'max' => 0.35],
    ];

    // Special skills
    protected $skills = [
        StunningBlow::class,
    ];
}
