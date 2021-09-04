<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class QueryExpressionTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\NullExpression
 */
class FilterClauseTest extends TestCase
{
    public function testGetPredicates()
    {
        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter('u.active', '==', 'true')
            ->get();

        $predicates = $result->getCommand(1)->getPredicates();

        self::assertEquals(1, count($predicates));
    }
}
