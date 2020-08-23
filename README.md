# FluentAQL

Fluent PHP query builder for [ArangoDB’s](https://www.arangodb.com) Query Language ([AQL](https://www.arangodb.com/docs/stable/aql/)).

		(badges)
## Table of contents
1. [Use Cases](#use-cases)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Usage](#usage)


## Purpose & use Cases
Using a query builder mainly makes the life of a programmer much easier. You can write cleaner code 
and be quicker at it. Which of course comes at the cost of application speed.

*If you need bleeding edge speed you will need to write your own queries.*

The use of a query builder has both pros and cons. It is up to you to decide what you need.

**Cons:**
1) *Sacrificing speed*
3) You still need to understand ArangoDB, AQL and the 'schema' of your database.
2) Slight variations between the query builder API and the raw AQL output which may be confusing

**Pros**
1) Programmatically compose queries (e.g. search facets).
2) Easy query decomposition 
3) Dry up your code.
4) Reduce AQL syntax bugs
5) Flexible expression input
6) IDE intellisense.

## Requirements
| FluentAQL           | ArangoDB          | PHP               |
| :------------------ | :---------------- | :---------------- |
| 0.x                 | 3.x *             | ^7.2               |

* ArangoDB regularly adds AQL functions and clauses in minor versions. So be sure to check the AQL documentation for the availability of specific features.

## Installation
The easiest way to install FluentAQL is through composer:
```
composer require laravel-freelancer-nl/fluentaql 
```

## Usage
First fire up a new query builder then fluently add AQL clauses on top. 
### Step 1: create a query builder:
```
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

...

$qb = new QueryBuilder();
```
### Step 2: compose your query:
*For Example:*
```
$qb->for('i', '1..100')->filter('i', '<', 50)->limit(10)->sort('i', 'desc')->return('i');
```

### Step 3: compile the query

```
$qb->get();
```
The query builder now contains the query, binds and collections (*) for you to send to ArangoDB through a client.
```
$query = $qb->query;
$binds = $qb->binds;
$collections = $qb->collections;
```
* Collections must be passed to ArangoDB in order to prevent deadlocks in certain cluster operations and transactions.

The generated AQL for this query is: 
```
FOR i IN 1..100 FILTER i < 50 LIMIT 10 SORT i DESC RETURN i
```

## API
See the following pages on details for the API

- [Query clauses](docs/query-clauses.md): how to search, select, sort and limit data 
- [Statement clauses](docs/statement-clauses.md): data manipulation & variable declaration
- [Functions](docs/functions.md): a list of all supported AQL functions
- [Subqueries](docs/subqueries.md): how to create subqueries, joins etc.
- [Data binding & referencing](docs/binds-references.md) 

### (Always) bind user input
No matter what, never trust user input and always bind it. 
``` 
$qb->bind('your data') 
```

Binds are registered in order and given an id. If you want to specify the bind name yourself you can add it: 
```
$qb->bind('your data', 'your-bind-id')
```

## References & resources 
### ArangoDB
- [ArangoDB](https://arangodb.com) 
- [AQL documentation](https://www.arangodb.com/docs/stable/aql/)
### ArangoDB PHP clients
- https://github.com/arangodb/arangodb-php
- https://github.com/sandrokeil/arangodb-php-client
