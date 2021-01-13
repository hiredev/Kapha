<?php

namespace App\EventListener;

use App\Entity\Usuario;
use Doctrine\Persistence\Event\LifecycleEventArgs;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\String\Slugger\AsciiSlugger;


class MyEntityListener
{    
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args){
        $this->preQuery($args);
    }

    public function preUpdate(LifecycleEventArgs $args){
        $this->preQuery($args);        
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        /*
        $entityManager = $args->getObjectManager();
        $entityManager->persist($entity);
        $entityManager->flush();
        */
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        /*
        $entityManager = $args->getObjectManager();
        $entityManager->persist($entity);
        $entityManager->flush();
        */
    }    


    protected function preQuery(LifecycleEventArgs $args){
        $entity = $args->getObject();

        if (method_exists($entity, 'setFecha')){
            $entity->setFecha(new \Datetime());         
        }

        if (method_exists($entity, 'setSlug')){
            $slug = '';

            if (method_exists($entity, 'getNombre')){
                $slug .= $entity->getNombre() . ' ';
            }

            if (method_exists($entity, 'getApellido')){
                $slug .= $entity->getApellido() . ' ';
            }

            if (method_exists($entity, 'getTitulo')){
                $slug .= $entity->getTitulo() . ' ';
            }

            $slug .= $entity->getId();

            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($slug);
            $entity->setSlug($slug);
        }


        if ($entity instanceof Usuario){
            $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($encoded);            
        }        
    }
}