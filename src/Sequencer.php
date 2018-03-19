<?php

namespace Chivincent\Snowflake;

interface Sequencer
{
    /**
     * Tell snowflake to wait for next millisecond.
     *
     * @int
     */
    const WAIT_FOR_NEXT_TIME = -1;

    /**
     * Get next sequence number.
     *
     * @param int $timestamp
     * @return int
     */
    public static function next(int $timestamp): int;
}
