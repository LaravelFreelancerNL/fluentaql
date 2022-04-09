<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;
use stdClass;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasQueryClauses
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasSupportCommands
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\RawClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\ForClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\FilterClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\SearchClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\CollectClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\IntoClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\KeepClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\AggregateClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\WithCountClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\SortClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\LimitClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\ReturnClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\OptionsClause
 * @covers \LaravelFreelancerNL\FluentAQL\Clauses\WindowClause
 * @covers  \LaravelFreelancerNL\FluentAQL\Traits\CompilesPredicates
 * @covers  \LaravelFreelancerNL\FluentAQL\Traits\ValidatesOperators
 * @covers  \LaravelFreelancerNL\FluentAQL\Traits\ValidatesReferences
 * @covers  \LaravelFreelancerNL\FluentAQL\Traits\NormalizesExpressions
 */
class QueryClausesTest extends TestCase
{
    public function testForClause()
    {
        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->get();
        self::assertEquals('FOR u IN users', $result->query);

        $result = (new QueryBuilder())
            ->for('u')
            ->get();
        self::assertEquals('FOR u IN', $result->query);

        $result = (new QueryBuilder())
            ->for(['v', 'e', 'p'], 'graph')
            ->get();
        self::assertEquals('FOR v, e, p IN graph', $result->query);
    }

    public function testForClauseWithCollectionBind()
    {
        $qb = new QueryBuilder();
        $qb->for('u', '@@users')
            ->get();

        self::assertEquals('FOR u IN @@'
            . $qb->getQueryId() . '_1', $qb->query);
    }

    public function testForClauseInExpression()
    {
        $aqb = (new QueryBuilder());
        $aqb = $aqb->let('x', [1,2,3,4])
            ->for(
                'u',
                $aqb->if(
                    [1, '>', 0],
                    'x',
                    []
                )
            )
            ->get();

        self::assertEquals('LET x = [1,2,3,4] FOR u IN (1 > 0) ? x : []', $aqb->query);
    }

    public function testFilterClause()
    {
        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter('u.active', '==', 'true')
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter('u.active', '==', 'true', 'OR')
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter([['u.active', '==', 'true'], ['u.age', '>', 18]])
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true AND u.age > 18', $result->query);
    }

    public function testFilterOnNullValueCanUseAllLogicalOperators()
    {
        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter('doc.attribute', '!=', null)
            ->return('doc')
            ->get();
        self::assertEquals('FOR doc IN documents FILTER doc.attribute != null RETURN doc', $result->query);

        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter([['doc.attribute1', '!=', null], ['doc.attribute2', '!=', null], ['doc.attribute3', '!=', null]])
            ->return('doc')
            ->get();
        self::assertEquals(
            'FOR doc IN documents FILTER doc.attribute1 != null AND doc.attribute2 != null'
            . ' AND doc.attribute3 != null RETURN doc',
            $result->query
        );
    }

    public function testFiltersAreSeparatedByComparisonOperators()
    {
        $filter = [
            ['doc.attribute1', '!=', 'null', 'OR'],
            ['doc.attribute2', '!=', null, 'OR'],
            ['doc.attribute3', '!=', 'null', 'OR'],
        ];
        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter($filter)
            ->get();
        self::assertEquals(
            'FOR doc IN documents FILTER doc.attribute1 != null OR doc.attribute2 != null OR doc.attribute3 != null',
            $result->query
        );
    }

    public function testFiltersWithCaseInsensitiveLogicalOperators()
    {
        $filter = [
            ['doc.attribute1', '!=', 'null', 'or'],
            ['doc.attribute2', '!=', 'null', 'Or'],
            ['doc.attribute3', '!=', 'null', 'OR'],
        ];
        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter($filter)
            ->get();
        self::assertEquals(
            'FOR doc IN documents FILTER doc.attribute1 != null OR doc.attribute2 != null OR doc.attribute3 != null',
            $result->query
        );
    }

    public function testFiltersWithRecursiveFilters()
    {
        $filter = [
            ['doc.attribute1', '==', 'null', 'OR'],
            [
                ['doc.attribute2', '!=', null, 'AND'],
                ['doc.attribute3', '!=', 'null', 'OR']
            ]
        ];
        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter($filter)
            ->get();
        self::assertEquals(
            'FOR doc IN documents FILTER doc.attribute1 == null AND (doc.attribute2 != null OR doc.attribute3 != null)',
            $result->query
        );
    }

    public function testFiltersWithRecursiveFiltersStartingArray()
    {
        $filter = [
            [
                ['doc.attribute1', '!=', null, 'AND'],
                ['doc.attribute2', '!=', 'null', 'OR']
            ],
            ['doc.attribute3', '==', 'null', 'OR']
        ];
        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter($filter)
            ->get();
        self::assertEquals(
            'FOR doc IN documents FILTER (doc.attribute1 != null OR doc.attribute2 != null) OR doc.attribute3 == null',
            $result->query
        );
    }

    public function testFiltersWithEmptyStringValue()
    {
        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter('doc.test', '==', '')
            ->get();
        self::assertEquals(
            'FOR doc IN documents FILTER doc.test == @' . $result->getQueryId() . '_1',
            $result->query
        );
    }

    public function testSearchClause()
    {
        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->search('u.active', '==', 'true')
            ->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->search('u.active', '==', 'true', 'OR')
            ->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->search([['u.active', '==', 'true'], ['u.age', '==', null]])
            ->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true AND u.age == null', $result->query);
    }

    public function testCollectClause()
    {
        $result = (new QueryBuilder())
            ->collect()
            ->get();
        self::assertEquals('COLLECT', $result->query);

        $result = (new QueryBuilder())
            ->collect('doc', 'expression')
            ->get();
        self::assertEquals('COLLECT doc = @' . $result->getQueryId() . '_1', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->collect('hometown', 'u.city')
            ->get();
        self::assertEquals('FOR u IN Users COLLECT hometown = u.city', $result->query);
    }

    public function testCollectMultipleClause()
    {
        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->collect([['hometown', 'u.city'], ['surname', 'u.surname']])
            ->return(['hometown', 'surname'])
            ->get();
        self::assertEquals(
            'FOR u IN Users COLLECT hometown = u.city, surname = u.surname'
            . ' RETURN [hometown,surname]',
            $result->query
        );
    }

    public function testIntoClause()
    {
        $result = (new QueryBuilder())
            ->into('groupsVariable')
            ->get();
        self::assertEquals('INTO groupsVariable', $result->query);

        $result = (new QueryBuilder())
            ->into('groupsVariable', 'projectionExpression')
            ->get();
        self::assertEquals('INTO groupsVariable = @' . $result->getQueryId() . '_1', $result->query);

        $result = (new QueryBuilder())
            ->into('groupsVariable', '{ 
    "name" : u.name, 
    "isActive" : u.status == "active"
  }')->get();
        self::assertEquals('INTO groupsVariable = @' . $result->getQueryId() . '_1', $result->query);
    }

    public function testKeepClause()
    {
        $result = (new QueryBuilder())
            ->keep('variableName')
            ->get();
        self::assertEquals('KEEP variableName', $result->query);
    }

    public function testSortClause()
    {
        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort('u.name', 'DESC')
            ->get();
        self::assertEquals('FOR u IN Users SORT u.name DESC', $result->query);

        $result = (new QueryBuilder())
            ->sort('null')
            ->get();
        self::assertEquals('SORT null', $result->query);

        $result = (new QueryBuilder())
            ->sort()
            ->get();
        self::assertEquals('SORT null', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort('u.name')
            ->get();
        self::assertEquals('FOR u IN Users SORT u.name', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort('u.name', 'u.age')
            ->get();
        self::assertEquals('FOR u IN Users SORT u.name, u.age', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort('u.age', 'DESC')
            ->get();
        self::assertEquals('FOR u IN Users SORT u.age DESC', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort('u.name', 'u.age', 'DESC')
            ->get();
        self::assertEquals('FOR u IN Users SORT u.name, u.age DESC', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort('u.name', 'u.age', 'DESC', 'u.city')
            ->get();
        self::assertEquals('FOR u IN Users SORT u.name, u.age DESC, u.city', $result->query);
    }

    public function testSortClauseWithFunctionArgument()
    {
        $qb = new QueryBuilder();
        $result = $qb->for('u', 'users')
            ->sort($qb->rand())
            ->get();
        self::assertEquals('FOR u IN users SORT RAND()', $result->query);
    }

    public function testLimitClause()
    {
        $result = (new QueryBuilder())
            ->limit(4)
            ->get();
        self::assertEquals('LIMIT 4', $result->query);

        $result = (new QueryBuilder())
            ->limit(4, 5)
            ->get();
        self::assertEquals('LIMIT 4, 5', $result->query);
    }

    public function testWithCountClause()
    {
        $result = (new QueryBuilder())
            ->withCount('countVariableName')
            ->get();
        self::assertEquals('WITH COUNT INTO countVariableName', $result->query);
    }

    public function testAggregateClause()
    {
        $result = (new QueryBuilder())
            ->aggregate('variableName', 'aggregateExpression')
            ->get();
        self::assertEquals('AGGREGATE variableName = @' . $result->getQueryId() . '_1', $result->query);
    }

    public function testReturnClause()
    {
        $result = (new QueryBuilder())
            ->return('NEW.key')
            ->get();
        self::assertEquals('RETURN NEW.key', $result->query);

        $result = (new QueryBuilder())
            ->return('u.name')
            ->get();
        self::assertEquals('RETURN @' . $result->getQueryId() . '_1', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->return('u.name')
            ->get();
        self::assertEquals('FOR u IN Users RETURN u.name', $result->query);

        $result = (new QueryBuilder())
            ->return('1 + 1')
            ->get();
        self::assertEquals('RETURN @' . $result->getQueryId() . '_1', $result->query);

        $result = (new QueryBuilder())
            ->return('1 + 1', true)
            ->get();
        self::assertEquals('RETURN DISTINCT @' . $result->getQueryId() . '_1', $result->query);
    }

    public function testOptionsClause()
    {
        $options = new stdClass();
        $options->method = 'sorted';
        $result = (new QueryBuilder())
            ->options($options)
            ->get();
        self::assertEquals('OPTIONS {"method":"sorted"}', $result->query);

        $options = ['method' => 'sorted'];
        $result = (new QueryBuilder())
            ->options($options)
            ->get();
        self::assertEquals('OPTIONS {"method":"sorted"}', $result->query);
    }

    public function testWindowClause()
    {
        $result = (new QueryBuilder())
            ->window(['preceding' => 5, 'following' => 10])
            ->get();

        self::assertEquals('WINDOW {"preceding":5,"following":10}', $result->query);
    }

    public function testWindowClauseWithRange()
    {
        $result = (new QueryBuilder())
            ->for('t', 'observations')
            ->window(['preceding' => 5, 'following' => 10], 't.time')
            ->get();

        self::assertEquals(
            'FOR t IN observations WINDOW t.time WITH {"preceding":5,"following":10}',
            $result->query
        );
    }

    public function testWindowClauseUnbounded()
    {
        $result = (new QueryBuilder())
            ->for('t', 'observations')
            ->window(['preceding' => 'unbounded', 'following' => 10], 't.time')
            ->get();

        self::assertEquals(
            'FOR t IN observations WINDOW t.time WITH {"preceding":"unbounded","following":10}',
            $result->query
        );
    }
    public function testWindowClauseDurationBasedAggregation()
    {
        $qb = new QueryBuilder();
        $qb->for('t', 'observations')
            ->window(['preceding' => 'PT30M'], $qb->dateTimestamp('t.time'))
            ->get();

        self::assertEquals(
            'FOR t IN observations WINDOW DATE_TIMESTAMP(t.time) WITH {"preceding":"PT30M"}',
            $qb->get()->query
        );
    }
}
