<?php

use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses
 */
class ExpressionsTest extends TestCase
{
    /**
     * document function
     * @test
     */
    public function ListExpression()
    {
        $expression = new ListExpression([1, 2, 'You know nothing John Snow']);
        self::assertInstanceOf(ListExpression::class, $expression);
        self::assertEquals('[1,2,"You know nothing John Snow"]', (string) $expression);

        $expression = new ListExpression([1, [2, 3]]);
        self::assertEquals('[1,[2,3]]', (string) $expression);

        $expression = new ListExpression(['users/john', 'users/amy']);
        self::assertEquals('["users/john","users/amy"]', (string) $expression);
    }
}
