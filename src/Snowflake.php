<?php

namespace Chivincent\Snowflake;

class Snowflake
{
    public function __invoke(int $count = 1): array
    {
        return $this->gen($count);
    }

    public function gen(int $count = 1): array
    {
        $ret = [];

        while ($count--) {
            $time = $this->time();
            $machine = getenv('MACHINE_ID') ?: 0;
            $sequence = Sequence::next($time);

            while ($sequence === Sequence::WAIT_FOR_NEXT_TIME) {
                $time++;
                $sequence = Sequence::next($time);
            }

            $binTime = str_pad(decbin($time), 41, 0, STR_PAD_LEFT);
            $binMachine = str_pad(decbin($machine), 13, 0, STR_PAD_LEFT);
            $binSequence = str_pad(decbin($sequence), 9, 0, STR_PAD_LEFT);

            if (!is_integer($id = bindec($binTime . $binMachine . $binSequence))) {
                throw new \Exception('The bits of integer is more than PHP_INT_MAX');
            }

            array_push($ret, $id);
        }

        return $ret;
    }

    protected function time(): int
    {
        return (int) (microtime(true) * 1000);
    }
}