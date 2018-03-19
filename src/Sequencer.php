<?php

namespace Chivincent\Snowflake;

interface Sequencer
{
    /**
     * Get next sequence number.
     *
     * @param int $timestamp
     * @return int
     */
    public static function next(int $timestamp): int;
}