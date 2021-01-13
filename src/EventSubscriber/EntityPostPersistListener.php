<?php

namespace App\EventSubscriber;

use App\Entity\User;
//use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EntityPostPersistListener
{
    /*
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }*/

    // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args)
    {
        throw new \Exception();
        $entity = $args->getObject();

        $update = false;

        if (method_exists($entity, 'setFecha')){
            $entity->setFecha(new \Datetime());
            $update = true;
        }

        if ($entity instanceof User){
            $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($encoded);
            $update = true;
        }

        if($update){
            $entityManager = $args->getObjectManager();
            $entityManager->persist($entity);
            $entityManager->flush();
        }
    }
}