<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class QueryExpressionTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\FilterClause
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

    public function testFilterLeftOperandOnly()
    {
        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter('u.active')
            ->return('u')
            ->get();

        self::assertEquals('FOR u IN Users FILTER u.active RETURN u', $result->query);
    }
}
