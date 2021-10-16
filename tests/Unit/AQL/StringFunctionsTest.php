<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasStringFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesStringFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\NormalizesFunctions
 */
class StringFunctionsTest extends TestCase
{
    public function testConcat()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->concat('string', 'this', 'together'));
        self::assertEquals(
            'RETURN CONCAT(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, @'
            . $qb->getQueryId() . '_3)',
            $qb->get()->query
        );
    }

    public function testConcatSeparator()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->concatSeparator('-', 'string', 'this', 'together'));
        self::assertEquals(
            'RETURN CONCAT_SEPARATOR(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, @'
            . $qb->getQueryId() . '_3, @'
            . $qb->getQueryId() . '_4)',
            $qb->get()->query
        );
    }

    public function testContains()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->contains('foobarbaz', 'bar'));
        self::assertEquals(
            'RETURN CONTAINS(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, false)',
            $qb->get()->query
        );
    }

    public function testContainsReturnIndex()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->contains('foobarbaz', 'bar', true));
        self::assertEquals(
            'RETURN CONTAINS(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, true)',
            $qb->get()->query
        );
    }

    public function testLevenshteinDistance()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->levenshteinDistance("foobar", "bar"));
        self::assertEquals(
            'RETURN LEVENSHTEIN_DISTANCE(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2)',
            $qb->get()->query
        );
    }

    public function testRegexMatches()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->regexMatches('foobarbaz', 'bar', true));
        self::assertEquals(
            'RETURN REGEX_MATCHES(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, true)',
            $qb->get()->query
        );
    }

    public function testRegexReplace()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->regexReplace('foobarbaz', 'bar', 'bars', true));
        self::assertEquals(
            'RETURN REGEX_REPLACE(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, @'
            . $qb->getQueryId() . '_3, true)',
            $qb->get()->query
        );
    }

    public function testRegexSplit()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->regexSplit('foobarbaz', 'bar', true, 10));
        self::assertEquals(
            'RETURN REGEX_SPLIT(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, true, 10)',
            $qb->get()->query
        );
    }

    public function testRegexTest()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->regexTest('foobarbaz', 'bar', true));
        self::assertEquals(
            'RETURN REGEX_TEST(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, true)',
            $qb->get()->query
        );
    }

    public function testSplit()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->split('foobarbaz', 'bar', 10));
        self::assertEquals(
            'RETURN SPLIT(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, 10)',
            $qb->get()->query
        );
    }

    public function testSubstitute()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->substitute('foobarbaz', 'bar', 'bars', 10));
        self::assertEquals(
            'RETURN SUBSTITUTE(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2, @'
            . $qb->getQueryId() . '_3, 10)',
            $qb->get()->query
        );
    }

    public function testSubstring()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->substring('foobarbaz', 3, 10));
        self::assertEquals(
            'RETURN SUBSTRING(@'
            . $qb->getQueryId() . '_1, 3, 10)',
            $qb->get()->query
        );
    }

    public function testTokens()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->tokens("Lörem ipsüm, DOLOR SIT Ämet.", "text_de"));
        self::assertEquals(
            'RETURN TOKENS(@'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2)',
            $qb->get()->query
        );
    }

    public function testTrim()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->trim("   Lörem ipsüm, DOLOR SIT Ämet.", 1));
        self::assertEquals(
            'RETURN TRIM(@'
            . $qb->getQueryId() . '_1, 1)',
            $qb->get()->query
        );
    }

    public function testUpper()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->upper("   Lörem ipsüm, DOLOR SIT Ämet."));
        self::assertEquals(
            'RETURN UPPER(@'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testUuid()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->uuid());
        self::assertEquals('RETURN UUID()', $qb->get()->query);
    }

}
