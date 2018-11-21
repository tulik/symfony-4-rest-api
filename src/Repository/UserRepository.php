<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Interfaces\RepositoryInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * {@inheritdoc}
 *
 * @method User find($id, $lockMode = null, $lockVersion = null)
 * @method User findOneBy(array $criteria, array $orderBy = null)
 * @method User[] findAll()
 */
class UserRepository extends AbstractRepository implements RepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }
}
