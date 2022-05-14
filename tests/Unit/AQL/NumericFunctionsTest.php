<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasNumericFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesNumericFunctions
 */
class NumericFunctionsTest extends TestCase
{
    public function testAverage()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->average([1, 2, 3, 4]));
        self::assertEquals('LET x = AVERAGE([1,2,3,4])', $qb->get()->query);
    }

    public function testAvg()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->avg([1, 2, 3, 4]));
        self::assertEquals('LET x = AVERAGE([1,2,3,4])', $qb->get()->query);
    }

    public function testAverageWithReference()
    {
        $qb = new QueryBuilder();
        $qb->for('u', 'users')->filter($qb->average('u.houses'), '==', 2);
        self::assertEquals('FOR u IN users FILTER AVERAGE(u.houses) == 2', $qb->get()->query);
    }

    public function testCeil()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->ceil(0.4));
        self::assertEquals('LET x = CEIL(0.4)', $qb->get()->query);
    }

    public function testCosineSimilarity()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->cosineSimilarity([0,1], [1,0]));
        self::assertEquals('LET x = COSINE_SIMILARITY([0,1], [1,0])', $qb->get()->query);
    }

    public function testDecayGauss()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->decayGauss(41, 40, 5, 5, 0.5));
        self::assertEquals('LET x = DECAY_GAUSS(41, 40, 5, 5, 0.5)', $qb->get()->query);
    }

    public function testDecayGaussWithArray()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->decayGauss([20, 41], 40, 5, 5, 0.5));
        self::assertEquals('LET x = DECAY_GAUSS([20,41], 40, 5, 5, 0.5)', $qb->get()->query);
    }

    public function testDecayExp()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->decayExp(49.9889, 50, 0.001, 0.001, 0.2));
        self::assertEquals('LET x = DECAY_EXP(49.9889, 50, 0.001, 0.001, 0.2)', $qb->get()->query);
    }

    public function testDecayLinear()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->decayLinear(9.8, 0, 10, 0, 0.2));
        self::assertEquals('LET x = DECAY_LINEAR(9.8, 0, 10, 0, 0.2)', $qb->get()->query);
    }

    public function testFloor()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->floor(0.4));
        self::assertEquals('LET x = FLOOR(0.4)', $qb->get()->query);
    }

    public function testL1Distance()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->l1Distance([[1,2,3],[-1,-2,-3],[3,4,5],[-5,2,1]], [1,1,1]));
        self::assertEquals('LET x = L1_DISTANCE([[1,2,3],[-1,-2,-3],[3,4,5],[-5,2,1]], [1,1,1])', $qb->get()->query);
    }

    public function testL2Distance()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->l2Distance([[1,2,3],[-1,-2,-3],[3,4,5],[-5,2,1]], [1,1,1]));
        self::assertEquals('LET x = L2_DISTANCE([[1,2,3],[-1,-2,-3],[3,4,5],[-5,2,1]], [1,1,1])', $qb->get()->query);
    }

    public function testMax()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->max([1, 2, 3, 4]));
        self::assertEquals('LET x = MAX([1,2,3,4])', $qb->get()->query);
    }

    public function testMin()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->min([1, 2, 3, 4]));
        self::assertEquals('LET x = MIN([1,2,3,4])', $qb->get()->query);
    }

    public function testProduct()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->product([1, 2, 3, 4]));
        self::assertEquals('RETURN PRODUCT([1,2,3,4])', $qb->get()->query);
    }

    public function testRand()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->rand());
        self::assertEquals('RETURN RAND()', $qb->get()->query);
    }

    public function testRange()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->range(1.5, 2.5, 0.5));
        self::assertEquals('LET x = RANGE(1.5, 2.5, 0.5)', $qb->get()->query);
    }

    public function testRound()
    {
        $qb = new QueryBuilder();
        $qb->let('x', $qb->round(0.4));
        self::assertEquals('LET x = ROUND(0.4)', $qb->get()->query);
    }

    public function testSum()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->sum([1, 2, 3, 4]));
        self::assertEquals('RETURN SUM([1,2,3,4])', $qb->get()->query);
    }
}
