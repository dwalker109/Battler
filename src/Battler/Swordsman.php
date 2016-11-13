<?php

namespace dwalker109\Battler;

use dwalker109\Skills\LuckyStrike;

class Swordsman extends Battler
{
    // Attribute definitions
    protected $definitions = [
        'health' => ['min' => 40, 'max' => 60],
        'strength' => ['min' => 60, 'max' => 70],
        'defence' => ['min' => 20, 'max' => 30],
        'speed' => ['min' => 90, 'max' => 100],
        'luck' => ['min' => 0.3, 'max' => 0.5],
    ];

    // Special skills
    protected $skills = [
        LuckyStrike::class,
    ];
}
