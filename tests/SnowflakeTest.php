<?php

namespace Tests;

use Chivincent\Snowflake\Snowflake;
use PHPUnit\Framework\TestCase;

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
}
