<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasTypeFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesTypeFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\NormalizesFunctions
 */
class TypeFunctionsTest extends TestCase
{
    public function testToArray()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->toArray('whatever'));
        self::assertEquals(
            'RETURN TO_ARRAY(@'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testToList()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->toList('whatever'));
        self::assertEquals(
            'RETURN TO_ARRAY(@'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testToBool()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->toBool('whatever'));
        self::assertEquals(
            'RETURN TO_BOOL(@'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testToNumber()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->toNumber('whatever'));
        self::assertEquals(
            'RETURN TO_NUMBER(@'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testToString()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->toString(0.0000002));
        self::assertEquals(
            'RETURN TO_STRING(2.0E-7)',
            $qb->get()->query
        );
    }
}
