<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit\Functions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasGeoFunctions
 */
class StringFunctionsTest extends TestCase
{

    public function testConcat()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->concat('string', 'this', 'together'));
        self::assertEquals('RETURN CONCAT(@' . $qb->getQueryId() . '_1, @' . $qb->getQueryId() . '_2, @' . $qb->getQueryId() . '_3)', $qb->get()->query);
    }
}
