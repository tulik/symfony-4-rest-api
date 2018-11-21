<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Security\Voter\Review;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateReviewVoter extends Voter
{
    public const CAN_CREATE_REVIEW = 'CAN_CREATE_REVIEW';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        // you only want to vote if the attribute and subject are what you expect
        return self::CAN_CREATE_REVIEW === $attribute && null === $subject;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        // allow user to delete account
        if ($user instanceof User) {
            return true;
        }

        return false;
    }
}
