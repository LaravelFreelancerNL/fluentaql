<?php

use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * Class StructureTest
 *
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasQueryClauses.php
 */
class QueryClausesTest extends TestCase
{
    /**
     * raw AQL
     * @test
     */
    public function raw_aql()
    {
        $result = AQB::raw('FOR u IN Users FILTER u.email="test@test.com"')->get();
        self::assertEquals('FOR u IN Users FILTER u.email="test@test.com"', $result->query);

        //Todo: test bindings & collections
    }

    /**
    * collect clause
    * @test
    */
    public function collect_clause()
    {
        $result = AQB::collect()->get();
        self::assertEquals('COLLECT', $result->query);

        $result = AQB::collect('doc', 'expression')->get();
        self::assertEquals('COLLECT doc = @1_1', $result->query);

        $result = AQB::for('u', 'Users')->collect('hometown', 'u.city')->get();
        self::assertEquals('FOR u IN Users COLLECT hometown = u.city', $result->query);
    }

    /**
     * group clause
     * @test
     */
    public function group_clause()
    {
        $result = AQB::group('groupsVariable')->get();
        self::assertEquals('INTO groupsVariable', $result->query);

        $result = AQB::group('groupsVariable', 'projectionExpression')->get();
        self::assertEquals('INTO groupsVariable = @1_1', $result->query);

        $result = AQB::group('groupsVariable', '{ 
    "name" : u.name, 
    "isActive" : u.status == "active"
  }')->get();
        self::assertEquals('INTO groupsVariable = @1_1', $result->query);
    }

    /**
     * aggregate clause
     * @test
     */
    public function aggregate_clause()
    {
        $result = AQB::aggregate('variableName', 'aggregateExpression')->get();
        self::assertEquals('AGGREGATE variableName = @1_1', $result->query);
    }

    /**
     * keep clause
     * @test
     */
    public function keep_clause()
    {
        $result = AQB::keep('variableName')->get();
        self::assertEquals('KEEP variableName', $result->query);
    }

    /**
     * count clause
     * @test
     */
    public function count_clause()
    {
        $result = AQB::withCount('countVariableName')->get();
        self::assertEquals('WITH COUNT INTO countVariableName', $result->query);
    }

    /**
     * options clause
     * @test
     */
    public function options_clause()
    {
        $options = new stdClass();
        $options->method = 'sorted';
        $result = AQB::options($options)->get();
        self::assertEquals('OPTIONS {"method":"sorted"}', $result->query);

        $options =['method' => 'sorted'];
        $result = AQB::options($options)->get();
        self::assertEquals('OPTIONS {"method":"sorted"}', $result->query);
    }

    /**
     * 'for' clause syntax
     * @test
     */
    public function for_clause_syntax()
    {
        $result = AQB::for('u', 'users')->get();
        self::assertEquals('FOR u IN users', $result->query);

        $result = AQB::for('u')->get();
        self::assertEquals('FOR u IN', $result->query);

        $result = AQB::for(['v', 'e', 'p'], 'graph')->get();
        self::assertEquals('FOR v, e, p IN graph', $result->query);
    }

    /**
     * filter clause syntax
     * @test
     */
    public function filter_clause_syntax()
    {
        $result = AQB::for('u', 'Users')->filter('u.active', '==', 'true')->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->filter('u.active', '==', 'true', 'OR')->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->filter('u.active', 'true')->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->filter('u.active')->get();
        self::assertEquals('FOR u IN Users FILTER u.active == null', $result->query);

        $result = AQB::for('u', 'Users')->filter([['u.active', '==', 'true'], ['u.age']])->get();
        self::assertEquals('FOR u IN Users FILTER u.active == true AND u.age == null', $result->query);
    }

    /**
     * Search clause syntax
     * @test
     */
    public function search_clause_syntax()
    {
        $result = AQB::for('u', 'Users')->search('u.active', '==', 'true')->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->search('u.active', '==', 'true', 'OR')->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->search('u.active', 'true')->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true', $result->query);

        $result = AQB::for('u', 'Users')->search('u.active')->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == null', $result->query);

        $result = AQB::for('u', 'Users')->search([['u.active', '==', 'true'], ['u.age']])->get();
        self::assertEquals('FOR u IN Users SEARCH u.active == true AND u.age == null', $result->query);
    }


    /**
     * sort clause syntax
     * @test
     */
    public function sort_clause_syntax()
    {
        $result  = AQB::for('u', 'Users')->sort('u.name', 'DESC')->get();
        self::assertEquals('FOR u IN Users SORT u.name DESC', $result->query);

        $result  = AQB::sort('null')->get();
        self::assertEquals('SORT null', $result->query);

        $result  = AQB::sort()->get();
        self::assertEquals('SORT null', $result->query);

        $result  = AQB::for('u', 'Users')->sort(['u.name'])->get();
        self::assertEquals('FOR u IN Users SORT u.name', $result->query);

        $result  = AQB::for('u', 'Users')->sort(['u.name', 'u.age'])->get();
        self::assertEquals('FOR u IN Users SORT u.name, u.age', $result->query);

        $result  = AQB::for('u', 'Users')->sort([['u.age', 'DESC']])->get();
        self::assertEquals('FOR u IN Users SORT u.age DESC', $result->query);

        $result  = AQB::for('u', 'Users')->sort(['u.name', ['u.age', 'DESC']])->get();
        self::assertEquals('FOR u IN Users SORT u.name, u.age DESC', $result->query);

        $result  = AQB::for('u', 'Users')->sort(['u.name', 'DESC'])->get();
        self::assertNotEquals('FOR u IN Users SORT u.name DESC', $result->query);
    }

    /**
     * limit clause syntax
     * @test
     */
    public function limit_clause_syntax()
    {
        $result = AQB::limit(4)->get();
        self::assertEquals('LIMIT 4', $result->query);

        $result = AQB::limit(4, 5)->get();
        self::assertEquals('LIMIT 4, 5', $result->query);
    }

    /**
     * 'return' clause Syntax
     * @test
     */
    public function return_clause_syntax()
    {
        $result = AQB::return('u.name')->get();
        self::assertEquals('RETURN @1_1', $result->query);

        $result = AQB::for('u', 'Users')->return('u.name')->get();
        self::assertEquals('FOR u IN Users RETURN u.name', $result->query);

        $result = AQB::return("1 + 1")->get();
        self::assertEquals('RETURN @1_1', $result->query);

        $result = AQB::return("1 + 1", true)->get();
        self::assertEquals('RETURN DISTINCT @1_1', $result->query);
    }
}
