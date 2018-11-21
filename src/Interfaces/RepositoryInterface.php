<?php

declare(strict_types=1);

namespace App\Interfaces;

use Doctrine\ORM\QueryBuilder;

interface RepositoryInterface
{
    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder;
}
