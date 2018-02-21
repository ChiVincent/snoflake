<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Chivincent\Snowflake\Snowflake;

class SnowflakeTest extends TestCase
{
    public function testGenSingleUniqueID()
    {
        $snowflake = new Snowflake();
        $id = $snowflake->gen();

        $this->assertTrue(is_array($id));
        $this->assertCount(1, $id);
    }

    public function testGenMultipleUniqueIDs()
    {
        $snowflake = new Snowflake();
        $ids = $snowflake->gen(1000);

        $this->assertTrue(is_array($ids));
        $this->assertCount(1000, $ids);
    }

    public function testIDsAreUnique()
    {
        $snowflake = new Snowflake();
        $ids = $snowflake->gen(100000);

        $this->assertArrayIsUnique($ids);
    }

    protected function assertArrayIsUnique(array $array)
    {
        $this->assertTrue(
            count($array) === count(array_unique($array))
        );
    }
}
