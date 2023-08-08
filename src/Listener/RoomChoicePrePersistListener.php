<?php

namespace App\Listener;

use App\Entity\RoomChoice;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Uid\Uuid;

#[AsDoctrineListener(Events::prePersist)]
class RoomChoicePrePersistListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof RoomChoice) {
            return;
        }

        $entity->setUuid(
            Uuid::v4()
        );
    }
}