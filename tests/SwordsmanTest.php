<?php

use PHPUnit\Framework\TestCase;
use dwalker109\Battle\Battle;
use dwalker109\Battler\Battler;
use dwalker109\Battler\Swordsman;
use SebastianBergmann\PeekAndPoke\Proxy;

class SwordsmanTest extends TestCase
{
    public function testSwordsmanIsCreated()
    {
        $battle = $this->getMockBuilder(Battle::class)
            ->setConstructorArgs(['Battler', 'Battler'])
            ->getMock();

        $swordsman = new Swordsman('Swordsman', $battle);

        $this->assertInstanceOf(Battler::class, $swordsman);
        $this->assertInstanceOf(Swordsman::class, $swordsman);

        return $swordsman;
    }

    /**
     * @depends testSwordsmanIsCreated
     */
    public function testSwordsmanAttributesAreSane(Swordsman $swordsman)
    {
        $proxy = new Proxy($swordsman);

        foreach ($proxy->definitions as $key => $values) {
            $this->assertGreaterThanOrEqual(
                $proxy->definitions[$key]['min'],
                $swordsman->attr()->{$key}
            );

            $this->assertLessThanOrEqual(
                $proxy->definitions[$key]['max'],
                $swordsman->attr()->{$key}
            );
        }
    }
}
