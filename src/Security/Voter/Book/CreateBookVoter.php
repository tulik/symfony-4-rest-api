<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Security\Voter\Book;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateBookVoter extends Voter
{
    public const CAN_CREATE_BOOK = 'CAN_CREATE_BOOK';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        // you only want to vote if the attribute and subject are what you expect
        return self::CAN_CREATE_BOOK === $attribute && null === $subject;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // our previous business logic indicates that mods and admins can do it regardless
        foreach ($token->getRoles() as $role) {
            if (\in_array($role->getRole(), ['ROLE_MODERATOR', 'ROLE_ADMIN'])) {
                return true;
            }
        }

        return false;
    }
}
