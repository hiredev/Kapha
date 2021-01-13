<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class EntityPrePersistListener
{
    // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ($entity instanceof Product) {
            return;
        }

        $entityManager = $args->getObjectManager();
        // ... do something with the Product entity
    }
}