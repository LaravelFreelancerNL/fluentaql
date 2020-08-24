# AQL functions

This page gives an overview of the AQL functions supported by this query builder.

If you are missing a function please create an issue requesting it. In the meantime you can use the raw clause.

## Using functions
Functions always return expressions to be used in clauses and other functions.

Example: 
```
$qb = new QueryBuilder();
$qb->for('i', '1..100')
    ->filter('i', '<', $qb->max([0,1,10,25,50]))
    ->limit(10)
    ->sort('i', 'desc')
    ->return('i');
```


## Array functions

| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| count($value)         | [COUNT()](https://www.arangodb.com/docs/stable/aql/functions-array.html#count) |
| countDistinct($value) | [COUNT_DISTINCT()](https://www.arangodb.com/docs/stable/aql/functions-array.html#count) |
| first($value)         | [FIRST()](https://www.arangodb.com/docs/stable/aql/functions-array.html#first) | 
| last($value)          | [LAST()](https://www.arangodb.com/docs/stable/aql/functions-array.html#last) | 
| length($value)        | [LENGTH()](https://www.arangodb.com/docs/stable/aql/functions-array.html#length) | 


## Date functions

| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| dateNow() | [DATE_NOW()](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_now) | 
| dateIso8601(numeric&#124;string&#124;DateTime $date) | [DATE_ISO8601(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601) | 
| dateTimestamp(numeric&#124;string&#124;DateTime $date) | [DATE_TIMESTAMP(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_timestamp) | 
|  | []() | 
|  | []() | 
|  | []() | 
|  | []() | 

## GEO functions

| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| distance($latitude1, $longitude1, $latitude2, $longitude2) | [DISTANCE(latitude1, longitude1, latitude2, longitude2)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#distance) | 

## Miscellaneous functions

| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| document($collection, $id = null) | [DOCUMENT(collection, id)](https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#document) | 
| average($value) | []() | 
| avg($value) | []() | 
| max($value) | []() | 
| min($value) | []() | 
| rand() | []() | 
| sum($value) | []() | 
