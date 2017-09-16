<?php

namespace PhpDao;

class Factory
{
    public static function createQueryBuilder(array $options)
    {
        return new QueryBuilder(new Connection($options));
    }
}