<?php

namespace dwalker109;

class Tools
{
    /**
     * Return a random integer or float within the passed constraints.
     *
     * @param int|float $min
     * @param int|float $max
     *
     * @return int|float
     */
    public static function randomNumber($min, $max)
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
     * Return a boolean with a percentage chance of being true.
     *
     * @param int $percentage
     *
     * @return boolean
     */
    public static function percentChance(int $chance)
    {
        return self::randomNumber(1, 100) <= $chance;
    }
}
