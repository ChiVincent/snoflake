<?php

namespace Chivincent\Snowflake;

class Sequence implements Sequencer
{
    /**
     * Current sequence.
     *
     * @var int
     */
    protected static $sequence = 0;

    /**
     * Last sequence was generated timestamp.
     *
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

        self::$sequence = 0;
        return self::WAIT_FOR_NEXT_TIME;
    }
}
