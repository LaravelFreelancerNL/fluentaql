# FluentAQL

PHP query builder for the [ArangoDB](https://www.arangodb.com) Query Language ([AQL](https://www.arangodb.com/docs/stable/aql/)).

> Version 0.1.x: DO NOT USE IN PRODUCTION YET

## Installation
You may use composer to install FluentAQL:

``` composer require laravel-freelancer-nl/fluentaql ```

### Version compatibility
| FluentAQL           | ArangoDB          | PHP               |
| :------------------ | :---------------- | :---------------- |
| 0.x.x               | 3.5.x             | 7.2               |

You can use FluentAQL with older versions of ArangoDB, but specific AQL features won't work depending on the version 
you're using. The [official AQL documentation](https://www.arangodb.com/docs/stable/aql/) sometimes provides information on 
supported versions for clauses, functions and expressions.

## Usage
The Query Builder (QB) has fluent API where you create a new QueryBuilder object and chain AQL statements to create a query.

### Facade
For ease of use you can use the AQB facade to quickly generate a static version of the QB.  In the documentation we 
solely use the facade, however feel free to just instantiate the QB normally.

```(new QueryBuilder())->for('i', '1..100')->return('i')```

### Getting the query, bindings and collection list 
the ``get()`` method returns an array with the query, the bindings and a list of used collections.
```(new QueryBuilder())->for('i', '1..100')->return('i')->get()```

### Query execution
FluentAQL is solely a query builder so does not include any methods for execution.

You can execute queries with an [ArangoDB PHP driver](https://github.com/arangodb/arangodb-php).
Create a statement and feed it the returned data, then execute the statement.