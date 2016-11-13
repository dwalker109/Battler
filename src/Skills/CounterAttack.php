<?php

namespace dwalker109\Skills;

use dwalker109\Battler\Battler;

class CounterAttack implements SkillContract
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
     */
    public function activate(Battler $battler)
    {
        $opponent = $battler->battle->player_opponent;

        if ($battler->attr()->evaded) {
            $battler->battle->pushMessage("{$battler->attr()->name} activated skill Counter Attack");
            $opponent->takeDamage(10, $battler);
        }
    }
}
