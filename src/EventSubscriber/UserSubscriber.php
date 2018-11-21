<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements EventSubscriber
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * UserSubscriber constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postUpdate,
            Events::onFlush,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $user = $args->getEntity();

        if ($user instanceof User) {
            $this->encodePassword($user);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $user = $args->getEntity();

        if ($user instanceof User) {
            $this->encodePassword($user);
        }
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        $classMetadata = $em->getClassMetadata(User::class);

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof User) {
                $entity->setUsername($entity->getEmail());
                $uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
            }
        }
    }

    /**
     * @param User $user
     */
    protected function encodePassword(User $user): void
    {
        $encoded = $this->encoder->encodePassword($user, $user->getPlainPassword());

        $user->setPassword($encoded);
    }
}
