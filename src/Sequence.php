<?php

namespace Chivincent\Snowflake;

class Sequence
{
    /**
     * @var int
     */
    protected static $sequence;

    /**
     * @var int
     */
    protected static $lastTimestamp;

    public static function next(int $currentTimestamp): int
    {
        if (self::$lastTimestamp !== $currentTimestamp) {
            self::$sequence = 0;
            self::$lastTimestamp = $currentTimestamp;

            return 0;
        }

        if (self::$sequence !== 512) {
            return ++self::$sequence;
        }

        usleep(1000);
        self::$sequence = 0;
        return 0;
    }
}