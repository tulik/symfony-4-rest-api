<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('this')->select('this');

        return $qb;
    }
}
