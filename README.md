PostgreSQL Doctrine Type
========================

This library add support for useful postgresql types such as Point, DateRange or Arrays.

Usage
-----

In Symfony, you only need to update your DBAL config.

```
doctrine:
    dbal:
        types:
            jsonb: PostgreSQLDoctrineType\DBAL\Type\JsonType
            daterange: PostgreSQLDoctrineType\DBAL\Type\DateRangeType
            tsrange: PostgreSQLDoctrineType\DBAL\Type\TsRangeType
            varchar_array: PostgreSQLDoctrineType\DBAL\Type\VarcharArrayType
        mapping_types:
            json_array: jsonb  # redefine doctrine json_array to use jsonb
            daterange: daterange
            tsrange: tsrange
```
