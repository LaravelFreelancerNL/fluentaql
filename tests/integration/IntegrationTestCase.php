<?php

use ArangoDBClient\Statement;
use triagens\ArangoDb\Connection;
use ArangoDBClient\ConnectionOptions as ArangoConnectionOptions;

/**
 * Class IntegrationTestCase
 * Test FluentAQL queries against an actual database
 */
abstract class IntegrationTestCase extends TestCase
{

    protected static $connection;

    /**
     *
     */
    public static function setUpBeforeClass() : void
    {
        self::$connection = new Connection([
            ArangoConnectionOptions::OPTION_ENDPOINT => 'tcp://localhost:8529',
            ArangoConnectionOptions::OPTION_CONNECTION  => 'Keep-Alive',
            ArangoConnectionOptions::OPTION_AUTH_USER => '',
            ArangoConnectionOptions::OPTION_AUTH_PASSWD => null
        ]);

        self::$connection::setDatabase('onepiece');
    }

    public static function tearDownAfterClass() : void
    {
        self::$connection = null;
    }

    public static function statement($query, $bindings = [])
    {
        $statement = new Statement(self::$connection, ['query' => $query, 'bindVars' => $bindings]);

        $cursor = $statement->execute();

        return $cursor;
    }

}