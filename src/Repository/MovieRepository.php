<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Movie;
use App\Interfaces\RepositoryInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * {@inheritdoc}
 *
 * @method Movie find($id, $lockMode = null, $lockVersion = null)
 * @method Movie findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[] findAll()
 */
class MovieRepository extends AbstractRepository implements RepositoryInterface
{
    /**
     * BookRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }
}
