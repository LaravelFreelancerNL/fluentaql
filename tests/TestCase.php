<?php

declare(strict_types=1);

namespace Tests;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    protected $aqb;

    public function setUp(): void
    {
        $this->aqb = new QueryBuilder();
    }

    public function testDummy()
    {
        self::assertEquals(true, true);
    }
}
