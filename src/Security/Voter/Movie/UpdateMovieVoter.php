<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Security\Voter\Movie;

use App\Entity\Movie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UpdateMovieVoter extends Voter
{
    public const CAN_UPDATE_MOVIE = 'CAN_UPDATE_MOVIE';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        // you only want to vote if the attribute and subject are what you expect
        return self::CAN_UPDATE_MOVIE === $attribute && ($subject instanceof Movie || null === $subject);
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
