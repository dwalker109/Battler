<?php

use PHPUnit\Framework\TestCase;
use dwalker109\Battle\Battle;
use dwalker109\Battler\Battler;
use dwalker109\Battler\Grappler;
use SebastianBergmann\PeekAndPoke\Proxy;

class GrapplerTest extends TestCase
{
    public function testGrapplerIsCreated()
    {
        $battle = $this->getMockBuilder(Battle::class)
            ->setConstructorArgs(['Battler', 'Battler'])
            ->getMock();

        $grappler = new Grappler('Grappler', $battle);

        $this->assertInstanceOf(Battler::class, $grappler);
        $this->assertInstanceOf(Grappler::class, $grappler);

        return $grappler;
    }

    /**
     * @depends testGrapplerIsCreated
     */
    public function testGrapplerAttributesAreSane(Grappler $grappler)
    {
        $proxy = new Proxy($grappler);

        foreach ($proxy->definitions as $key => $values) {
            $this->assertGreaterThanOrEqual(
                $proxy->definitions[$key]['min'],
                $grappler->attr()->{$key}
            );

            $this->assertLessThanOrEqual(
                $proxy->definitions[$key]['max'],
                $grappler->attr()->{$key}
            );
        }
    }
}
