<?php

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

abstract class TestCase extends PhpUnitTestCase
{
    protected $aqb;

    public function setUp(): void
    {
        $this->aqb = new QueryBuilder();
    }
}
