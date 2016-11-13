<?php

namespace dwalker109\Skills;

use dwalker109\Battler\Battler;

interface SkillContract
{
    const PRE = 'pre';
    const POST = 'post';

    public function type();
    public function activate(Battler $battler);
}
