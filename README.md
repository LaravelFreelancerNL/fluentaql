# FluentAQL

Fluent PHP query builder for [ArangoDB’s](https://www.arangodb.com) Query Language ([AQL](https://www.arangodb.com/docs/stable/aql/)).

---------
# I’m archiving my ArangoDB PHP/Laravel packages

Due to the license changes ArangoDB introduced last year, it no longer makes sense for me to continue using the product or to invest further time in developing, maintaining, and improving these packages.

While building and running side projects is in many ways easier and more affordable than ever, the new license creates a significant barrier for my own projects.

I’ve genuinely enjoyed working with ArangoDB, and I still believe it is an excellent product. However, under the current licensing model, I can no longer justify the time required to support these packages. Time is my most limited resource, and I need to allocate it where it makes sense professionally.

As a result, I am archiving the following packages:

- The Laravel driver: https://github.com/LaravelFreelancerNL/laravel-arangodb
- The PHP client: https://github.com/LaravelFreelancerNL/arangodb-php-client
- The AQL query builder: https://github.com/LaravelFreelancerNL/fluentaql

If there is interest in continuing their development, you are welcome to fork them and maintain your own versions. Alternatively, if you would like to sponsor or hire me to continue maintaining them, please feel free to get in touch.

Thank you to everyone who has used, supported, or contributed to these packages.

So long, and thanks for all the fish.

Bas  
Laravel Freelancer NL
---------


[![Latest Version](https://poser.pugx.org/laravel-freelancer-nl/fluentaql/v/stable)](//packagist.org/packages/laravel-freelancer-nl/fluentaql)
![Github CI tests](https://github.com/LaravelFreelancerNL/fluentaql/workflows/Continuous%20Integration/badge.svg)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LaravelFreelancerNL/fluentaql/badges/quality-score.png?b=next)](https://scrutinizer-ci.com/g/LaravelFreelancerNL/fluentaql/?branch=next)
[![Code Coverage](https://scrutinizer-ci.com/g/LaravelFreelancerNL/fluentaql/badges/coverage.png?b=next)](https://scrutinizer-ci.com/g/LaravelFreelancerNL/fluentaql/?branch=next)
<a href="https://packagist.org/packages/laravel-freelancer-nl/fluentaql"><img src="https://poser.pugx.org/laravel-freelancer-nl/fluentaql/downloads" alt="Total Downloads"></a>
[![License](https://poser.pugx.org/laravel-freelancer-nl/fluentaql/license)](//packagist.org/packages/laravel-freelancer-nl/fluentaql)

## Table of contents
1. [Use Cases](#purpose)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Usage](#usage)

## Purpose
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
| 1.x                 | ^3.6              | ^8.0    |

* ArangoDB regularly adds AQL functions and clauses in minor versions. So be sure to check the AQL documentation for the availability of specific features.

## Installation
You know the drill:
```
composer require laravel-freelancer-nl/fluentaql 
```

## Before you begin: safety first!
FluentAQL is a query builder that focuses on making your life as a developer easier while maintaining the strength
and flexibility of AQL. It focuses on syntax checking of the provided expressions 
however that is not airtight if you don't bind user input.

**Always bind user input.**

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

### (Always) bind user input
No matter what, never trust user input and always bind it. 
``` 
$qb->bind('your data') 
```

Binds are registered in order and given an id. If you want to specify the bind name yourself you can add it: 
```
$qb->bind('your data', 'your-bind-id')
```

## Documentation
- API
    - [Query clauses](docs/api/query-clauses.md): how to search, select, sort and limit data 
    - [Statement clauses](docs/api/statement-clauses.md): data manipulation & variable declaration
    - [Graph clauses](docs/api/graph-clauses.md): graph traversals
    - [Functions](docs/api/functions.md): a list of all supported AQL functions
    - [Operator expressions](docs/api/operator-expressions.md): if() & calc() expressions
    - [Subqueries](docs/core-concepts/subqueries.md): how to create subqueries, joins etc.
- Core Concepts
    - [Terminology](docs/core-concepts/terminology.md): definitions of terms used in the documentation 
    - [Data binding](docs/core-concepts/data-binding.md): How to inject external data and collections
    - [Predicates](docs/core-concepts/predicates.md): How to use predicates in filter clauses and functions
    - [Subqueries](docs/core-concepts/subqueries.md): Subquery creation

## References & resources 

### ArangoDB
- [ArangoDB](https://arangodb.com) 
- [AQL documentation](https://www.arangodb.com/docs/stable/aql/)

## Related packages
* [ArangoDB PHP client](https://github.com/LaravelFreelancerNL/arangodb-php-client)
* [ArangoDB Laravel Driver](https://github.com/LaravelFreelancerNL/laravel-arangodb)


## Credits
- Pluma@arangodb

