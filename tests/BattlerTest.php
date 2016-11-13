<?php

use PHPUnit\Framework\TestCase;
use dwalker109\Battle\Battle;
use dwalker109\Battler\Battler;
use dwalker109\Battler\Swordsman;
use dwalker109\Skills\SkillContract;
use SebastianBergmann\PeekAndPoke\Proxy;

class BattlerTest extends TestCase
{
    public function testBattlerIsCreated()
    {
        $battle = $this->getMockBuilder(Battle::class)
            ->setConstructorArgs(['Battler', 'Battler'])
            ->getMock();

        $battler = new Swordsman('Swordsman', $battle);

        $this->assertInstanceOf(Battler::class, $battler);

        return $battler;
    }

    /**
     * @depends testBattlerIsCreated
     */
    public function testBattlerSkillsAreSane(Battler $battler)
    {
        $proxy = new Proxy($battler);

        $this->assertContainsOnlyInstancesOf(SkillContract::class, $proxy->pre_turn_skills);
        $this->assertContainsOnlyInstancesOf(SkillContract::class, $proxy->post_turn_skills);
    }

    /**
     * @depends testBattlerIsCreated
     */
    public function testTurnAttributesCanBeSet(Battler $battler)
    {
        $expected = [
            'name' => 'test',
            'type' => 'test',
            'was_hit' => false,
            'stunned' => false,
            'evaded' => false,
            'health' => 50,
            'strength' => 50,
            'defence' => 50,
            'speed' => 50,
            'luck' => 0.5,
        ];

        $battler->attr($expected);

        $this->assertEquals($expected, (array) $battler->attr());
    }

    /**
     * @depends testBattlerIsCreated
     */
    public function testStunnedBattlerCannotAttack(Battler $battler)
    {
        $opponent = clone $battler;

        $battler->attr(['stunned' => true]);

        $result = $battler->attack($opponent);

        $this->assertNotTrue($result);
    }

    /**
     * @depends testBattlerIsCreated
     */
    public function testLuckyBattlerCannotBeHit(Battler $battler)
    {
        $battler->attr(['stunned' => false]);

        $opponent = clone $battler;
        $opponent->attr(['luck' => 1.00]);

        $battler->attack($opponent);

        $this->assertTrue($opponent->attr()->evaded);
    }

    /**
     * @depends testBattlerIsCreated
     */
    public function testUnluckyBattlerCannotEvade(Battler $battler)
    {
        $battler->attr(['stunned' => false]);

        $opponent = clone $battler;
        $opponent->attr(['luck' => 0.00]);

        $battler->attack($opponent);

        $this->assertFalse($opponent->attr()->evaded);
    }

    /**
     * @depends testBattlerIsCreated
     */
    public function testAttackCausesDamage(Battler $battler)
    {
        $opponent = clone $battler;

        $opponent->attr([
            'luck' => 0.00,
            'defence' => 0,
        ]);

        $battler->attr([
            'strength' => 1,
        ]);

        $expected = $opponent->attr()->health - 1;

        $battler->attack($opponent);

        $this->assertEquals($expected, $opponent->attr()->health);
    }

    /**
     * @depends testBattlerIsCreated
     */
    public function testDefenceReducesDamage(Battler $battler)
    {
        $opponent = clone $battler;

        $opponent->attr([
            'luck' => 0.00,
            'defence' => 5,
        ]);

        $battler->attr([
            'strength' => 10,
        ]);

        $expected = $opponent->attr()->health - 5;

        $battler->attack($opponent);

        $this->assertEquals($expected, $opponent->attr()->health);
    }
}
