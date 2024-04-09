<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasArangoSearchFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesArangoSearchFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\ValidatesOperators
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\ValidatesExpressions
 */
class ArangoSearchFunctionsTest extends TestCase
{
    public function testAnalyzer()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->return($qb->analyzer('doc.text', '==', 'bar', 'text_en'));

        self::assertEquals(
            'FOR doc IN viewName RETURN ANALYZER(doc.text == "bar", @' . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testAnalyzerMultiplePredicates()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->return($qb->analyzer([
                ['doc.text', '==', 'foo'],
                ['doc.text', '==', 'bar', 'OR'],
            ], 'text_en'));

        self::assertEquals(
            'FOR doc IN viewName RETURN ANALYZER((doc.text == "foo" OR doc.text == "bar"), @'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testBoost()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->return($qb->boost('doc.text', '==', 'bar', 2.5));

        self::assertEquals(
            'FOR doc IN viewName RETURN BOOST(doc.text == "bar", 2.5)',
            $qb->get()->query
        );
    }

    public function testBoostMultiplePredicates()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->return($qb->boost([
                ['doc.text', '==', 'foo'],
                ['doc.text', '==', 'bar', 'OR'],
            ], 3));

        self::assertEquals(
            'FOR doc IN viewName RETURN BOOST((doc.text == "foo" OR doc.text == "bar"), 3)',
            $qb->get()->query
        );
    }

    public function testBm25()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->analyzer('bar', 'text_en'))
            ->sort($qb->bm25('doc'), 'desc')
            ->return('doc');

        self::assertEquals(
            'FOR doc IN viewName SEARCH ANALYZER("bar", @'
            . $qb->getQueryId() . '_1) SORT BM25(doc) desc RETURN doc',
            $qb->get()->query
        );
    }

    public function testBm25WithK()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->analyzer('bar', 'text_en'))
            ->sort($qb->bm25('doc', 0.75), 'desc')
            ->return('doc');

        self::assertEquals(
            'FOR doc IN viewName SEARCH ANALYZER("bar", @'
            . $qb->getQueryId() . '_1) SORT BM25(doc, 0.75) desc RETURN doc',
            $qb->get()->query
        );
    }

    public function testBm25WithKAndB()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->analyzer('bar', 'text_en'))
            ->sort($qb->bm25('doc', 0.75, 1), 'desc')
            ->return('doc');

        self::assertEquals(
            'FOR doc IN viewName SEARCH ANALYZER("bar", @'
            . $qb->getQueryId() . '_1) SORT BM25(doc, 0.75, 1) desc RETURN doc',
            $qb->get()->query
        );
    }

    public function testTfidf()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->analyzer('bar', 'text_en'))
            ->sort($qb->tfidf('doc'), 'desc')
            ->return('doc');

        self::assertEquals(
            'FOR doc IN viewName SEARCH ANALYZER("bar", @'
            . $qb->getQueryId() . '_1) SORT TFIDF(doc) desc RETURN doc',
            $qb->get()->query
        );
    }

    public function testTfidfNormalize()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->analyzer('bar', 'text_en'))
            ->sort($qb->tfidf('doc', true), 'desc')
            ->return('doc');

        self::assertEquals(
            'FOR doc IN viewName SEARCH ANALYZER("bar", @'
            . $qb->getQueryId() . '_1) SORT TFIDF(doc, true) desc RETURN doc',
            $qb->get()->query
        );
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->exists('doc.text'))
            ->return('doc');

        self::assertEquals(
            'FOR doc IN viewName SEARCH EXISTS(doc.text) RETURN doc',
            $qb->get()->query
        );
    }

    public function testExistsWithTypeCheck()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->exists('doc.text', 'string'))
            ->return('doc');

        self::assertEquals(
            'FOR doc IN viewName SEARCH EXISTS(doc.text, @'
            . $qb->getQueryId() . '_1) RETURN doc',
            $qb->get()->query
        );
    }

    public function testInRange()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->inRange('doc.value', 3, 5))
            ->return('doc.value');

        self::assertEquals(
            'FOR doc IN viewName SEARCH IN_RANGE(doc.value, 3, 5) RETURN doc.value',
            $qb->get()->query
        );
    }

    public function testInRangeIncludeEdges()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->inRange('doc.value', 3, 5, true, false))
            ->return('doc.value');

        self::assertEquals(
            'FOR doc IN viewName SEARCH IN_RANGE(doc.value, 3, 5, true, false) RETURN doc.value',
            $qb->get()->query
        );
    }

    public function testLevenshteinMatch()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->levenshteinMatch('doc.text', 'quikc', 2))
            ->return('doc.text');

        self::assertEquals(
            'FOR doc IN viewName SEARCH LEVENSHTEIN_MATCH(doc.text, @'
            . $qb->getQueryId() . '_1, 2) RETURN doc.text',
            $qb->get()->query
        );
    }

    public function testLevenshteinMatchWithOptionalParameters()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->levenshteinMatch('doc.text', 'quikc', 2, false, 0, 'now'))
            ->return('doc.text');

        self::assertEquals(
            'FOR doc IN viewName SEARCH LEVENSHTEIN_MATCH(doc.text, @'
            . $qb->getQueryId() . '_1, 2, false, 0, @'
            . $qb->getQueryId() . '_2) RETURN doc.text',
            $qb->get()->query
        );
    }

    public function testLike()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->analyzer($qb->like('doc.text', 'foo%b_r'), 'text_en'))
            ->return('doc.text');

        self::assertEquals(
            'FOR doc IN viewName SEARCH ANALYZER(LIKE(doc.text, @'
            . $qb->getQueryId() . '_2), @'
            . $qb->getQueryId() . '_1) RETURN doc.text',
            $qb->get()->query
        );
    }

    public function testNgramMatch()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->nGramMatch('doc.text', 'quick fox'))
            ->return('doc.text');

        self::assertEquals(
            'FOR doc IN viewName SEARCH NGRAM_MATCH(doc.text, @'
            . $qb->getQueryId() . '_1) RETURN doc.text',
            $qb->get()->query
        );
    }

    public function testNgramMatchOptionalArgs()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->nGramMatch('doc.text', 'quick fox', 0.8, 'bigram'))
            ->return('doc.text');

        self::assertEquals(
            'FOR doc IN viewName SEARCH NGRAM_MATCH(doc.text, @'
            . $qb->getQueryId() . '_1, 0.8, @'
            . $qb->getQueryId() . '_2) RETURN doc.text',
            $qb->get()->query
        );
    }

    public function testPhrase()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->phrase('doc.text', 'quick fox'))
            ->return('doc.text');

        self::assertEquals(
            'FOR doc IN viewName SEARCH PHRASE(doc.text, @'
            . $qb->getQueryId() . '_1) RETURN doc.text',
            $qb->get()->query
        );
    }

    public function testPhraseWithAnalyzer()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->phrase('doc.text', 'quick fox', 'text_en'))
            ->return('doc.text');

        self::assertEquals(
            'FOR doc IN viewName SEARCH PHRASE(doc.text, @'
            . $qb->getQueryId() . '_1, @'
            . $qb->getQueryId() . '_2) RETURN doc.text',
            $qb->get()->query
        );
    }

    public function testPhraseWithSkipsPartsAndAnalyzer()
    {
        $qb = new QueryBuilder();
        $qb->for('doc', 'viewName')
            ->search($qb->phrase('doc.text', 'ipsum', 2, 'amet', 'text_en'))
            ->return('doc.text');

        self::assertEquals(
            'FOR doc IN viewName SEARCH PHRASE(doc.text, @'
            . $qb->getQueryId() . '_1, 2, @'
            . $qb->getQueryId() . '_2, @'
            . $qb->getQueryId() . '_3) RETURN doc.text',
            $qb->get()->query
        );
    }
}
