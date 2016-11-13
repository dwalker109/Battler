<?php
use PHPUnit\Framework\TestCase;

use dwalker109\Tools;

class ToolsTest extends TestCase
{
    public function testRandomNumber()
    {
        $min = 6;
        $max = 7;
        $result = Tools::randomNumber($min, $max);

        $this->assertGreaterThanOrEqual($min, $result);
        $this->assertLessThanOrEqual($max, $result);
    }

    public function testPercentChance()
    {
        $result = Tools::percentChance(100);
        $this->assertTrue($result);

        $result = Tools::percentChance(0);
        $this->assertFalse($result);
    }
}
