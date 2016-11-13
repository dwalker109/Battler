<?php
use PHPUnit\Framework\TestCase;

use dwalker109\Battle\Battle;
use dwalker109\Battler\Battler;
use SebastianBergmann\PeekAndPoke\Proxy;

class BattleTest extends TestCase
{
    public function testBattleIsCreated()
    {
        $battle = new Battle('Player 1', 'Player 2');
        
        $this->assertInstanceOf(Battle::class, $battle);
        
        return $battle;
    }
    
    /**
     * @depends testBattleIsCreated
     */
    public function testCombatantsExist(Battle $battle)
    {
        $this->assertInstanceOf(Battler::class, $battle->player_current);
        $this->assertInstanceOf(Battler::class, $battle->player_opponent);
    }
    
    /**
     * @depends testBattleIsCreated
     */
    public function testBattleIsActive(Battle $battle)
    {
        $this->assertTrue($battle->is_active);
    }
    
    /**
     * @depends testBattleIsCreated
     */
    public function testPlayersRotateEachTurn(Battle $battle)
    {
        $current = $battle->player_current;
        
        $battle->calculateTurn();
        
        $this->assertNotEquals($current, $battle->player_current);
        $this->assertEquals($current, $battle->player_opponent);
    }
    
    public function testBattleIsDrawnAfterTurnLimit()
    {
        $battle = new Battle('Player 1', 'Player 2');
        $proxy = new Proxy($battle);
        
        for ($n = $proxy->current_turn; $n <= $proxy->max_turns; $n++) {
            $attributes = [
                'defence' => PHP_INT_MAX,
                'strength' => PHP_INT_MIN,
            ];
            
            $battle->player_current->attr($attributes);
            $battle->player_opponent->attr($attributes);
            
            $battle->calculateTurn();
        }
        
        $this->assertTrue($proxy->current_turn === $proxy->max_turns + 1);
        $this->assertFalse($battle->is_active);
    }
    
    public function testSimpleWinCondition()
    {
        $battle = new Battle('Player 1', 'Player 2');

        $winner = $battle->player_current;
        $loser = $battle->player_opponent;
        
        while ($battle->is_active) {
            $winner->attr([
                'strength' => PHP_INT_MAX,
                'defence' => PHP_INT_MAX,
            ]);
            
            $loser->attr([
                'strength' => 0,
                'defence' => 0,
            ]);
            
            $battle->calculateTurn();
        }

        $messages = $battle->popMessages();
        $this->assertStringEndsWith('is the winner!', array_pop($messages));
    }
}
