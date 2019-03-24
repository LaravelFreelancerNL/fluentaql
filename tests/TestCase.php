<?php
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

abstract class TestCase extends PhpUnitTestCase
{
    public $aqb;

    public function setUp() : void
    {
        $this->aqb = new \LaravelFreelancerNL\FluentAQL\Query();
    }

}