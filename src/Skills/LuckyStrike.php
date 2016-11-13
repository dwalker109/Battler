<?php

namespace dwalker109\Skills;
use dwalker109\Battler\Battler;

class LuckyStrike implements SkillContract
{
    /**
     * Return type (pre or post turn) skill.
     *
     * @return string; 
     */
    public function type()
    {
        return static::PRE;
    }
    
    /**
     * Activate the skill.
     *
     * @param Battler $battler
     *
     * @return void;
     */
    public function activate(Battler $battler)
    {
        if ($battler->random(0, 100) <= 20) {
            $battler->attr(['strength' => $battler->attr()->strength * 2]);
            $battler->battle->pushMessage("{$battler->attr()->name} activated skill Lucky Strike");
        }
    }
}