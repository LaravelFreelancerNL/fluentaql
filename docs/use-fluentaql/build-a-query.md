# Build a query
You can easily create a new query as follows:
Create a QueryBuilder object and fluently string together query, statement and graph clauses.
```php
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

$qb = new QueryBuilder();
$qb = $qb-> for(‘u’, ‘users’)->filter(‘u.surname’, ‘Stark’)->return(‘u’);
```
