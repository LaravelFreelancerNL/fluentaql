# AQL functions

This page gives an overview of the AQL functions supported by this query builder.

If you are missing a function please create an issue requesting it. In the meantime you can use the raw clause.

## Using functions
Functions always return expressions to be used in clauses or other expressions.

Example: 
```
$qb = new QueryBuilder();
$qb->for('i', '1..100')
    ->filter('i', '<', $qb->max([0,1,10,25,50]))
    ->limit(10)
    ->sort('i', 'desc')
    ->return('i');
```

## ArangoSearch functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| analyzer($leftOperand, $comparisonOperand, $rightOperand, $analyzer) | [ANALYZER(expr, analyzer)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#analyzer) |
| bm25($doc, $k = null,$b = null) | [BM25(doc, k, b)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#bm25) |
| boost($leftOperand, $comparisonOperand, $rightOperand, $analyzer) | [BOOST(expr, boost)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#boost) |
| exists($path,$type = null) | [EXISTS(path)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#exists) |
| inRange($path, $low, $high, $includeLow, $includeHigh) | [IN_RANGE(path, low, high, includeLow, includeHigh)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#in_range) |
| levenshteinMatch($path, $target, $distance, $transpositions, $maxTerms, $prefix) | [LEVENSHTEIN_MATCH(path, target, distance, transpositions, maxTerms, prefix)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#levenshtein_match) |
| like($path, $search)| [LIKE(path, search)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#like) |
| nGramMatch($path, $target, $threshold, $analyzer) | [NGRAM_MATCH(path, target, threshold, analyzer)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#ngram_match) |
| phrase(path, $phrasePart1, $skipTokens1, ... $phrasePartN, $skipTokensN ], $analyzer) | [PHRASE(path, [ phrasePart1, skipTokens1, ... phrasePartN, skipTokensN ], analyzer)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#phrase) |
| tfidf($doc, $normalize) | [TFIDF(doc, normalize)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#tfidf) |


## Array functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| count($value)         | [COUNT(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#count) |
| countDistinct($value) | [COUNT_DISTINCT(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#count) |
| first($value)         | [FIRST(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#first) | 
| last($value)          | [LAST(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#last) | 
| length($value)        | [LENGTH(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#length) | 


## Date functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| dateNow() | [DATE_NOW()](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_now) | 
| dateIso8601(numeric&#124;string&#124;DateTime $date) | [DATE_ISO8601(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601) | 
| dateTimestamp(numeric&#124;string&#124;DateTime $date) | [DATE_TIMESTAMP(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_timestamp) | 
| dateYear($date) | [DATE_YEAR(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_year) | 
| dateMonth($date) | [DATE_MONTH(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_month) | 
| dateDay($date) | [DATE_DAY(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_day) | 
| dateHour($date) | [DATE_HOUR(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_hour) | 
| dateMinute($date) | [DATE_MINUTE(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_minute) | 
| dateSecond($date) | [DATE_SECOND(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_second) | 
| dateMillisecond($date) | [DATE_MILLISECOND(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_millisecond) | 
| dateFormat($date, $format) | [DATE_FORMAT(date, format)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_format) | 

## GEO functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| distance($latitude1, $longitude1, $latitude2, $longitude2) | [DISTANCE(latitude1, longitude1, latitude2, longitude2)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#distance) | 

## Miscellaneous functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| document($collection, $id = null) | [DOCUMENT(collection, id)](https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#document) | 

## Numeric functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| average($value) | [AVERAGE(numArray](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#average) | 
| avg($value) | [AVG(numArray](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#average) | 
| max($value) | [MAX(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#max) | 
| min($value) | [MIN(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#min) | 
| rand() | [RAND()](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#rand) | 
| sum($value) | [SUM(numArray)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#sum) | 

## String functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| concat(...$arguments) | [CONCAT(value1, value2, ... valueN)](https://www.arangodb.com/docs/stable/aql/functions-string.html#concat) | 
