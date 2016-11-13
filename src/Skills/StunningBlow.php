<?php

namespace dwalker109\Skills;

use dwalker109\Battler\Battler;
use dwalker109\Tools;

class StunningBlow implements SkillContract
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
        $opponent = $battler->battle->player_opponent;
        
        if ($opponent->attr()->was_hit && Tools::percentageChance(2)) {
            $battler->battle->pushMessage("{$battler->attr()->name} activated skill Stunning Blow");
            $opponent->attr(['stunned' => true]);
        }
    }
}
