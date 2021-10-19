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
## Using unsupported functions
ArangoDB supports many more functions than those listed below. 
It is possible to use those by using [rawExpressions](raw-aql.md#raw-expressions).


## Supported functions
The following functions are directly supported in FluentAql.

### ArangoSearch functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| analyzer($leftOperand, $comparisonOperand, $rightOperand, $analyzer) | [ANALYZER(expr, analyzer)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#analyzer) |
| bm25($doc, $k, $b) | [BM25(doc, k, b)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#bm25) |
| boost($leftOperand, $comparisonOperand, $rightOperand, $analyzer) | [BOOST(expr, boost)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#boost) |
| exists($path, $type) | [EXISTS(path)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#exists) |
| inRange($path, $low, $high, $includeLow, $includeHigh) | [IN_RANGE(path, low, high, includeLow, includeHigh)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#in_range) |
| levenshteinMatch($path, $target, $distance, $transpositions, $maxTerms, $prefix) | [LEVENSHTEIN_MATCH(path, target, distance, transpositions, maxTerms, prefix)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#levenshtein_match) |
| like($path, $search)| [LIKE(path, search)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#like) |
| nGramMatch($path, $target, $threshold, $analyzer) | [NGRAM_MATCH(path, target, threshold, analyzer)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#ngram_match) |
| phrase(path, $phrasePart1, $skipTokens1, ... $phrasePartN, $skipTokensN, $analyzer) | [PHRASE(path, [ phrasePart1, skipTokens1, ... phrasePartN, skipTokensN ], analyzer)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#phrase) |
| tfidf($doc, $normalize) | [TFIDF(doc, normalize)](https://www.arangodb.com/docs/stable/aql/functions-arangosearch.html#tfidf) |


### Array functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| append($array, $values, $unique) | [APPEND(anyArray, values, unique)](https://www.arangodb.com/docs/stable/aql/functions-array.html#append) |
| count($value)         | [COUNT(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#count) |
| countDistinct($value) | [COUNT_DISTINCT(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#count) |
| first($value)         | [FIRST(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#first) | 
| flatten($array, $depth)| [FLATTEN(anyArray, depth)](https://www.arangodb.com/docs/stable/aql/functions-array.html#flatten) | 
| last($value)          | [LAST(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#last) | 
| length($value)        | [LENGTH(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#length) | 
| shift($array)         | [SHIFT(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#shift) | 
| union($arrays)        | [UNION(array1, array2, ... arrayN)](https://www.arangodb.com/docs/stable/aql/functions-array.html#union) | 
| unionDistinct($arrays) | [UNION_DISTINCT(array1, array2, ... arrayN)](https://www.arangodb.com/docs/stable/aql/functions-array.html#union_distinct) | 
| unique($array)        | [UNIQUE(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-array.html#unique) | 


### Date functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| dateCompare($date1, $date2, $unitRangeStart, $unitRangeEnd) | [DATE_COMPARE(date1, date2, unitRangeStart, unitRangeEnd)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_compare) | 
| dateDay($date) | [DATE_DAY(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_day) | 
| dateFormat($date, $format) | [DATE_FORMAT(date, format)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_format) | 
| dateHour($date) | [DATE_HOUR(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_hour) | 
| dateIso8601($date) | [DATE_ISO8601(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601) | 
| dateLocalToUtc($date, $timezone, $zoneInfo) | [DATE_LOCALTOUTC(date, timezone, zoneinfo)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_localtoutc) | 
| dateMonth($date) | [DATE_MONTH(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_month) | 
| dateNow() | [DATE_NOW()](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_now) | 
| dateMillisecond($date) | [DATE_MILLISECOND(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_millisecond) | 
| dateMinute($date) | [DATE_MINUTE(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_minute) | 
| dateRound($date, $amount, $unit) | [DATE_ROUND(date, amount, unit)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_round) | 
| dateSecond($date) | [DATE_SECOND(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_second) | 
| dateTimestamp($date) | [DATE_TIMESTAMP(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_timestamp) | 
| dateTrunc($date, $unit) | [DATE_TRUNC(date, unit)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_trunc) | 
| dateUtcToLocal($date, $timezone, $zoneInfo) | [DATE_UTCTOLOCAL(date, timezone, zoneinfo)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_utctolocal) | 
| dateYear($date) | [DATE_YEAR(date)](https://www.arangodb.com/docs/stable/aql/functions-date.html#date_year) | 


### Document functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| attributes($document, $removeInternal, $sort) | [ATTRIBUTES(document, removeInternal, sort)](https://www.arangodb.com/docs/stable/aql/functions-document.html#attributes) | 
| keepAttributes($document, $attributes) | [KEEP(document, attributeName1, attributeName2, ... attributeNameN)](https://www.arangodb.com/docs/stable/aql/functions-document.html#keep) | 
| matches($document, $examples,$returnIndex) | [MATCHES(document, examples, returnIndex)](https://www.arangodb.com/docs/stable/aql/functions-document.html#matches) | 
| merge(...$documents) | [MERGE(...documents)](https://www.arangodb.com/docs/stable/aql/functions-document.html#merge) | 
| parseIdentifier($documentHandle) | [PARSE_IDENTIFIER(documentHandle)](https://www.arangodb.com/docs/stable/aql/functions-document.html#parse_identifier) | 
| unset($document, $attributes) | [UNSET(document, ...attributeName)](https://www.arangodb.com/docs/stable/aql/functions-document.html#unset) | 


### GEO functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| distance($latitude1, $longitude1, $latitude2, $longitude2) | [DISTANCE(latitude1, longitude1, latitude2, longitude2)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#distance) | 
| geoArea($geoJson, $ellipsoid) | [GEO_AREA(geoJson, ellipsoid)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_area) | 
| geoContains($geoJsonA, $geoJsonB) | [GEO_AREA(GEO_CONTAINS(geoJsonA, geoJsonB)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_contains) | 
| geoDistance($geoJsonA, $geoJsonB, $ellipsoid) | [GEO_DISTANCE(geoJsonA, geoJsonB, ellipsoid)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_distance) | 
| geoEquals($geoJsonA, $geoJsonB) | [GEO_EQUALS(geoJsonA, geoJsonB)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_equals) | 
| geoIntersects($geoJsonA, $geoJsonB) | [GEO_INTERSECTS(geoJsonA, geoJsonB)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_intersects) | 
| geoInRange($geoJsonA, $geoJsonB, $low, $high, $includeLow, $includeHigh) | [GEO_IN_RANGE(geoJsonA, geoJsonB, low, high, includeLow, includeHigh)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_in_range) | 
| geoLineString($points) | [GEO_LINESTRING(points)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_linestring) | 
| geoMultiLineString($points) | [GEO_MULTILINESTRING(points)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_multilinestring) | 
| geoMultiPoint($points) | [GEO_MULTIPOINT(points)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_multipoint) | 
| geoPoint($longitude, $latitude) | [GEO_POINT(longitude, latitude)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_point) | 
| geoPolygon($points) | [GEO_POLYGON(points)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_polygon) | 
| geoMultiPolygon($points) | [GEO_MULTIPOLYGON(points)](https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_multipolygon) | 


### Miscellaneous functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| document($collection, $id) | [DOCUMENT(collection, id)](https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#document) | 
| firstDocument(...$arguments) | [FIRST_DOCUMENT(alternative, ...)](https://www.arangodb.com/docs/stable/aql/functions-miscellaneous.html#first_document) | 


### Numeric functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| average($value) | [AVERAGE(numArray](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#average) | 
| avg($value) | [AVG(numArray](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#average) | 
| ceil($value) | [CEIL(value)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#ceil) | 
| floor($value) | [FLOOR(value)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#floor) | 
| max($value) | [MAX(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#max) | 
| min($value) | [MIN(anyArray)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#min) | 
| product($array) | [PRODUCT(numArray)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#min) | 
| rand() | [RAND()](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#rand) | 
| range($start, $stop, $step) | [RANGE(start, stop, step)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#range) | 
| round($value) | [ROUND(value)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#round) | 
| sum($value) | [SUM(numArray)](https://www.arangodb.com/docs/stable/aql/functions-numeric.html#sum) | 


### String functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| concat(...$arguments) | [CONCAT(value1, value2, ... valueN)](https://www.arangodb.com/docs/stable/aql/functions-string.html#concat) | 
| concatSeparator($separator, $values) | [CONCAT_SEPARATOR(separator, value1, value2, ... valueN)](https://www.arangodb.com/docs/stable/aql/functions-string.html#concat_separator) | 
| levenshteinDistance($value1, $value2) | [LEVENSHTEIN_DISTANCE(value1, value2)](https://www.arangodb.com/docs/stable/aql/functions-string.html#levenshtein_distance) | 
| lower($value)| [LOWER(value)](https://www.arangodb.com/docs/stable/aql/functions-string.html#lower) | 
| regexMatches($text, $regex, $caseInsensitive)| [REGEX_MATCHES(text, regex, caseInsensitive)](https://www.arangodb.com/docs/stable/aql/functions-string.html#regex_matches) | 
| regexReplace($text, $regex, $replacement, $caseInsensitive)| [REGEX_REPLACE(text, search, replacement, caseInsensitive)](https://www.arangodb.com/docs/stable/aql/functions-string.html#regex_replace) | 
| regexSplit($text, $splitExpression, $caseInsensitive, $limit)| [REGEX_SPLIT(text, splitExpression, caseInsensitive, limit)](https://www.arangodb.com/docs/stable/aql/functions-string.html#regex_split) | 
| regexTest($text, $search, $caseInsensitive)| [REGEX_TEST(text, search, caseInsensitive)](https://www.arangodb.com/docs/stable/aql/functions-string.html#regex_test) | 
| split($value, $separator, $limit)| [SPLIT(value, separator, limit)](https://www.arangodb.com/docs/stable/aql/functions-string.html#split) | 
| substitute($text, $search, $replace, $limit)| [SUBSTITUTE(value, search, replace, limit)](https://www.arangodb.com/docs/stable/aql/functions-string.html#substitute) | 
| substring($value, $offset, $length)| [SUBSTRING(value, offset, length)](https://www.arangodb.com/docs/stable/aql/functions-string.html#substitute) | 
| tokens($input, $analyzer)| [TOKENS(input, analyzer)](https://www.arangodb.com/docs/stable/aql/functions-string.html#tokens) | 
| trim($value, $type)| [TRIM(value, type)](https://www.arangodb.com/docs/stable/aql/functions-string.html#trim) | 
| upper($value)| [UPPER(value)](https://www.arangodb.com/docs/stable/aql/functions-string.html#upper) | 
| uuid()| [UUID()](https://www.arangodb.com/docs/stable/aql/functions-string.html#uuid) | 


### Type functions
| Description           | AQL Function |
| :-------------------- | :-------------------------------------------------------------------------------------------- |
| toArray($value) | [TO_ARRAY(value)](https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_array) | 
| toBool($value) | [TO_BOOL(value)](https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_bool) | 
| tolist($value) | [TO_LIST(value)](https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_list) | 
| toNumber($value) | [TO_NUMBER(value)](https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_number) | 
| toString($value) | [TO_STRING(value)](https://www.arangodb.com/docs/stable/aql/functions-type-cast.html#to_string) | 
