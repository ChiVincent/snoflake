<?php

namespace Chivincent\Snowflake;

class Sequence
{
    /**
     * @const int
     */
    const WAIT_FOR_NEXT_TIME = -1;

    /**
     * @var int
     */
    protected static $sequence = 0;

    /**
     * @var int
     */
    protected static $lastTimestamp;

    public static function next(int $currentTimestamp): int
    {
        if (self::$lastTimestamp !== $currentTimestamp) {
            self::$sequence = 0;
            self::$lastTimestamp = $currentTimestamp;

            return self::$sequence++;
        }

        if (self::$sequence !== 511) {
            return self::$sequence++;
        }

        usleep(1000);
        self::$sequence = 0;
        return self::WAIT_FOR_NEXT_TIME;
    }
}