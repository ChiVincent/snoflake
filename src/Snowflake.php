<?php

namespace Chivincent\Snowflake;

class Snowflake
{
    /**
     * @var string
     */
    protected $sequencer;

    /**
     * Bits of machine id length.
     *
     * @var int
     */
    protected $machineLength;

    /**
     * Bits of sequence id length.
     *
     * @var int
     */
    protected $sequenceLength;

    /**
     * Snowflake constructor.
     *
     * @param string $sequencer
     * @param int $machineLength
     * @param int $sequenceLength
     * @throws \Exception
     */
    public function __construct(string $sequencer = StaticSequence::class, int $machineLength = 13, int $sequenceLength = 9)
    {
        if (!is_subclass_of($sequencer, Sequencer::class)) {
            throw new \Exception('Sequencer should be implemented Chivincent\\Snowflake\\Sequencer');
        }
        if (!is_int($machineLength)) {
            throw new \Exception('Machine ID length should be integer.');
        }
        if (!is_int($sequenceLength)) {
            throw new \Exception('Sequence length should be integer.');
        }
        if ($machineLength + $sequenceLength !== 22) {
            throw new \Exception('The sum of Machine ID length and Sequence length should be 22 bits.');
        }

        $this->sequencer = $sequencer;
        $this->machineLength = $machineLength;
        $this->sequenceLength = $sequenceLength;
    }

    /**
     * Generate single snowflake id.
     *
     * @return int
     * @throws \Exception
     */
    public function __invoke(): int
    {
        return $this->gen()[0];
    }

    /**
     * Generate multiple snowflake ids.
     *
     * @param int $count
     * @return array
     * @throws \Exception
     */
    public function gen(int $count = 1): array
    {
        $ret = [];

        while ($count--) {
            $time = $this->time();
            $machine = getenv('MACHINE_ID') ?: 0;
            $sequence = forward_static_call("$this->sequencer::next", $time);

            while ($sequence === Sequencer::WAIT_FOR_NEXT_TIME) {
                $time++;
                usleep(1000);
                $sequence = forward_static_call("$this->sequencer::next", $time);
            }

            $binTime = str_pad(decbin($time), 41, 0, STR_PAD_LEFT);
            $binMachine = str_pad(decbin($machine), $this->machineLength, 0, STR_PAD_LEFT);
            $binSequence = str_pad(decbin($sequence), $this->sequenceLength, 0, STR_PAD_LEFT);

            if (!is_int($id = bindec($binTime . $binMachine . $binSequence))) {
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
