<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * Class StructureTest.
 *
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\hasQueryClauses.php
 */
class QueryClausesTest extends TestCase
{

    public function testRawAql()
    {
        $result = (new QueryBuilder())
            ->raw('FOR u IN Users FILTER u.email="test@test.com"')
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.email="test@test.com"', $result->query);

        //Todo: test bindings & collections
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
        self::assertEquals('COLLECT doc = @1_1', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->collect('hometown', 'u.city')
            ->get();
        self::assertEquals('FOR u IN Users COLLECT hometown = u.city', $result->query);
    }

    public function testGroupClause()
    {
        $result = (new QueryBuilder())
            ->group('groupsVariable')
            ->get();
        self::assertEquals('INTO groupsVariable', $result->query);

        $result = (new QueryBuilder())
            ->group('groupsVariable', 'projectionExpression')
            ->get();
        self::assertEquals('INTO groupsVariable = @1_1', $result->query);

        $result = (new QueryBuilder())
            ->group('groupsVariable', '{ 
    "name" : u.name, 
    "isActive" : u.status == "active"
  }')->get();
        self::assertEquals('INTO groupsVariable = @1_1', $result->query);
    }

    public function testAggregateClause()
    {
        $result = (new QueryBuilder())
            ->aggregate('variableName', 'aggregateExpression')
            ->get();
        self::assertEquals('AGGREGATE variableName = @1_1', $result->query);
    }

    public function testKeepClause()
    {
        $result = (new QueryBuilder())
            ->keep('variableName')
            ->get();
        self::assertEquals('KEEP variableName', $result->query);
    }

    public function testCountClause()
    {
        $result = (new QueryBuilder())
            ->withCount('countVariableName')
            ->get();
        self::assertEquals('WITH COUNT INTO countVariableName', $result->query);
    }

    public function testOptionsClause()
    {
        $options = new \stdClass();
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

    public function testForClauseSyntax()
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

    public function testFilterSyntax()
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
            ->filter('u.active', 'true')
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter('u.active')
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == null', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->filter([['u.active', '==', 'true'], ['u.age']])
            ->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true AND u.age == null', $result->query);
    }

    public function testFilterOnNullValueCanUseAllLogicalOperators()
    {
        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter('doc.attribute', '!=')
            ->return('doc')
            ->get();
        self::assertEquals('FOR doc IN documents FILTER doc.attribute != null RETURN doc', $result->query);

        $result = (new QueryBuilder())
            ->for('doc', 'documents')
            ->filter([['doc.attribute1', '!='], ['doc.attribute2', '!='], ['doc.attribute3', '!=']])
            ->return('doc')
            ->get();
        self::assertEquals(
            'FOR doc IN documents FILTER doc.attribute1 != null AND doc.attribute2 != null'
            . ' AND doc.attribute3 != null RETURN doc',
            $result->query
        );
    }

    public function testFiltersAreSeperatedByComparisonOperators()
    {
        $filter = [
            ['doc.attribute1', '!=', 'null', 'OR'],
            ['doc.attribute2', '!=', 'null', 'OR'],
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

    public function testSearchClauseSyntax()
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
            ->search('u.active', 'true')
            ->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->search('u.active')
            ->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == null', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->search([['u.active', '==', 'true'], ['u.age']])
            ->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true AND u.age == null', $result->query);
    }

    public function testSortSyntax()
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
            ->sort(['u.name'])
            ->get();
        self::assertEquals('FOR u IN Users SORT u.name', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort(['u.name', 'u.age'])
            ->get();
        self::assertEquals('FOR u IN Users SORT u.name, u.age', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort([['u.age', 'DESC']])
            ->get();
        self::assertEquals('FOR u IN Users SORT u.age DESC', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort(['u.name', ['u.age', 'DESC']])
            ->get();
        self::assertEquals('FOR u IN Users SORT u.name, u.age DESC', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->sort(['u.name', 'DESC'])
            ->get();
        self::assertNotEquals('FOR u IN Users SORT u.name DESC', $result->query);
    }

    public function testLimitSyntax()
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

    public function testReturnSyntax()
    {
        $result = (new QueryBuilder())
            ->return('NEW.key')
            ->get();
        self::assertEquals('RETURN NEW.key', $result->query);

        $result = (new QueryBuilder())
            ->return('u.name')
            ->get();
        self::assertEquals('RETURN @1_1', $result->query);

        $result = (new QueryBuilder())
            ->for('u', 'Users')
            ->return('u.name')
            ->get();
        self::assertEquals('FOR u IN Users RETURN u.name', $result->query);

        $result = (new QueryBuilder())
            ->return('1 + 1')
            ->get();
        self::assertEquals('RETURN @1_1', $result->query);

        $result = (new QueryBuilder())
            ->return('1 + 1', true)
            ->get();
        self::assertEquals('RETURN DISTINCT @1_1', $result->query);
    }
}
