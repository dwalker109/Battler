<?php

use PHPUnit\Framework\TestCase;
use dwalker109\Battle\Battle;
use dwalker109\Battler\Battler;
use dwalker109\Battler\Brute;
use SebastianBergmann\PeekAndPoke\Proxy;

class BruteTest extends TestCase
{
    public function testBruteIsCreated()
    {
        $battle = $this->getMockBuilder(Battle::class)
            ->setConstructorArgs(['Battler', 'Battler'])
            ->getMock();

        $brute = new Brute('Brute', $battle);

        $this->assertInstanceOf(Battler::class, $brute);
        $this->assertInstanceOf(Brute::class, $brute);

        return $brute;
    }

    /**
     * @depends testBruteIsCreated
     */
    public function testBruteAttributesAreSane(Brute $brute)
    {
        $proxy = new Proxy($brute);

        foreach ($proxy->definitions as $key => $values) {
            $this->assertGreaterThanOrEqual(
                $proxy->definitions[$key]['min'],
                $brute->attr()->{$key}
            );

            $this->assertLessThanOrEqual(
                $proxy->definitions[$key]['max'],
                $brute->attr()->{$key}
            );
        }
    }
}
