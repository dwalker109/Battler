<?php

namespace dwalker109\Skills;

use dwalker109\Battler\Battler;
use dwalker109\Tools;

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
     */
    public function activate(Battler $battler)
    {
        if (Tools::percentChance(5)) {
            $battler->attr(['strength' => $battler->attr()->strength * 2]);
            $battler->battle->pushMessage("{$battler->attr()->name} activated skill Lucky Strike");
        }
    }
}
