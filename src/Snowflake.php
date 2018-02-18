<?php

namespace Chivincent\Snowflake;

class Snowflake
{
    public function __invoke(int $count): array
    {
        return $this->gen($count);
    }

    public function gen(int $count): array
    {
        $ret = [];

        while ($count--) {
            $time = $this->time();
            $machine = getenv('MACHINE_ID') ?: 0;
            $sequence = Sequence::next($time);

            $binTime = str_pad(decbin($time), 41, 0, STR_PAD_LEFT);
            $binMachine = str_pad(decbin($machine), 14, 0, STR_PAD_LEFT);
            $binSequence = str_pad(decbin($sequence), 0, 0, STR_PAD_LEFT);

            array_push($ret, bindec($binTime . $binMachine . $binSequence));
        }

        return $ret;
    }

    protected function time(): int
    {
        return (int) (microtime(true) * 1000);
    }
}