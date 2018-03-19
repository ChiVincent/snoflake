<?php

namespace Chivincent\Snowflake;

class Snowflake
{
    protected $sequencer;

    public function __construct(string $sequencer = Sequence::class)
    {
        if (!is_subclass_of($sequencer, Sequencer::class)) {
            throw new \Exception('Sequencer should be implemented Chivincent\\Snowflake\\Sequencer');
        }

        $this->sequencer = $sequencer;
    }

    public function __invoke(int $count = 1): int
    {
        return $this->gen($count)[0];
    }

    public function gen(int $count = 1): array
    {
        $ret = [];

        while ($count--) {
            $time = $this->time();
            $machine = getenv('MACHINE_ID') ?: 0;
            $sequence = $this->sequencer::next($time);

            while ($sequence === $this->sequencer::WAIT_FOR_NEXT_TIME) {
                $time++;
                usleep(1000);
                $sequence = $this->sequencer::next($time);
            }

            $binTime = str_pad(decbin($time), 41, 0, STR_PAD_LEFT);
            $binMachine = str_pad(decbin($machine), 13, 0, STR_PAD_LEFT);
            $binSequence = str_pad(decbin($sequence), 9, 0, STR_PAD_LEFT);

            if (!is_integer($id = bindec($binTime . $binMachine . $binSequence))) {
                throw new \Exception('The bits of integer is larger than PHP_INT_MAX');
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
