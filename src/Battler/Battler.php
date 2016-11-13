<?php

namespace dwalker109\Battler;

use dwalker109\Battle\Battle;
use dwalker109\Skills\SkillContract;

abstract class Battler
{
    // Handle to the current battle
    public $battle;
    
    // Battler detail and baseline/turn attributes
    private $name;
    private $attributes;
    private $turn_attributes;
    
    // Special skills
    private $pre_turn_skills = [];
    private $post_turn_skills = [];
    
    // Attribute definitions
    protected $definitions = [
        'health' => ['min' => 0, 'max' => 100],
        'strength' => ['min' => 0, 'max' => 100],
        'defence' => ['min' => 0, 'max' => 100],
        'speed' => ['min' => 0, 'max' => 100],
        'luck' => ['min' => 0.00, 'max' => 1.00],
    ];
    
    // Special skills
    protected $skills = [];
    
    /**
     * Initialise a new combatant and generate attributes.
     *
     * @param string $name
     * @param Battle $battle
     *
     * @return void
     */
    public function __construct($name, Battle $battle)
    {
        $this->name = $name;
        $this->battle = $battle;
        
        // Build attributes from definitions
        foreach ($this->definitions as $attribute => $constraints) {
            $this->attributes[$attribute] = $this->random(
                $constraints['min'], $constraints['max']
            );
        }
        
        // Add additional attributes
        $this->attributes += [
            // Display
            'name' => ucfirst($this->name),
            'type' => (new \ReflectionClass($this))->getShortName(),
            
            // Status and effects
            'stunned' => false,
            'evaded' => false,
        ];
        
        // Register special skills
        foreach ($this->skills as $skill) {
            $skill = new $skill;
            
            switch ($skill->type()) {
                case SkillContract::PRE:
                    $this->pre_turn_skills[] = $skill;
                    break;
                
                case SkillContract::POST:
                    $this->post_turn_skills[] = $skill;
                    break;
            }
        }
        
        // Prepare for next turn.
        $this->initNextTurn();
    }

    /**
     * Set/return attribute properties for this turn.
     *
     * @param array $values
     *
     * @return srdClass
     */
    public function attr($values = null)
    {
        // Set any turn attributes passed, ignoring any which don't exist
        if (is_array($values)) {
            $filtered = array_intersect_key($values, $this->turn_attributes);
            $this->turn_attributes = array_merge($this->turn_attributes, $filtered);
        }
        
        return (object) $this->turn_attributes;
    }
    
    /**
     * Return a random integer or float within the passed constraints.
     *
     * @param int|float $min
     * @param int|float $max
     *
     * @return int|float
     */
    public function random($min, $max)
    {
        if (is_integer($min) && is_integer($max)) {
            return mt_rand($min, $max);
        }
        
        if (is_float($min) && is_float($max)) {
            return ($min + lcg_value() * (abs($max - $min)));
        }
        
        // Something unusual was passed - return integer 0
        return 0;
    }
    
    /**
     * Attack the opposing Battler.
     *
     * @param Battler $opponent
     *
     * @return void
     */
    public function attack(Battler $opponent)
    {
        // Use opponent's luck to calculate if the attack should miss
        switch ($opponent->evade()) {
            case true:
                $this->battle->pushMessage(
                    "{$this->attr()->name} was unlucky and missed their attack"
                );
                
                break;
            
            case false;
            default:
                $this->battle->pushMessage(
                    "{$this->attr()->name} attacked with {$this->attr()->strength} strength"
                );
                
                $opponent->defend($this);
                
                break;
        }
    }
    
    /**
     * Defend against an attack from the opposing Battler.
     *
     * @param Battler $opponent
     *
     * @return void
     */
    public function defend(Battler $opponent)
    {
        // Calculate damage (prevent negative damage) and subtract from health
        $damage = max(0, $opponent->attr()->strength - $this->attr()->defence);
        $this->attributes['health'] -= $damage;
        
        $this->battle->pushMessage("{$this->attr()->name} received {$damage} damage");
        
        // End the battle if attack was fatal
        if (!$this->isAlive()) {
            $this->battle->is_active = false;
            $this->battle->pushMessage("{$opponent->attr()->name} is the winner!");
        }
    }
    
    /**
     * Try to evade an incoming attack.
     *
     * @return boolean
     */
    public function evade()
    {
        $evaded = $this->random(0.00, 1.00) < $this->attr()->luck;
        
        $this->attr(compact('evaded'));
        
        return $evaded;
    }
    
    /**
     * Check for death.
     *
     * @return void
     */
    public function isAlive()
    {
        if ($this->attr()->health <= 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Run any registered pre turn special skills.
     *
     * @return void;
     */
    public function preTurnSkills()
    {
        foreach($this->pre_turn_skills as $skill) {
            $skill->activate($this);
        }
    }
    
    /**
     * Run any registered post turn special skills.
     *
     * @return void;
     */
    public function postTurnSkills()
    {
        foreach($this->post_turn_skills as $skill) {
            $skill->activate($this);
        }
    }
    
    /**
     * Prepate for next turn.
     *
     * @return void
     */
    public function initNextTurn()
    {
        $this->turn_attributes = $this->attributes;
    }
}
