<?php

namespace App\EventSubscriber;

use App\Entity\Lesson;
use App\Entity\User;
use App\Managers\LessonManager;
use App\Repository\CuentaZoomRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\String\Slugger\AsciiSlugger;


class MyEntityListener
{
    private $passwordEncoder;
    private $lessonManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, LessonManager $lessonManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->lessonManager = $lessonManager;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->preQuery($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
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


    protected function preQuery(LifecycleEventArgs $args)
    {

        $entity = $args->getObject();

        if ($entity instanceof Lesson) {
            $entity = $this->lessonManager->createZoomMeeting($entity);

        }

        if (method_exists($entity, 'setFecha')) {
            $entity->setFecha(new \Datetime());
        }

        if (method_exists($entity, 'setSlug')) {
            $slug = '';
dd($entity);
            if (method_exists($entity, 'getFirstName')) {
                $slug .= $entity->getFirstName() . ' ';
            }

            if (method_exists($entity, 'getLastName')) {
                $slug .= $entity->getLastName() . ' ';
            }
            if (method_exists($entity, 'getTitulo')) {
                $slug .= $entity->getTitulo() . ' ';
            }

            if (method_exists($entity, 'getTitle')) {
                $slug .= $entity->getTitle() . ' ';
            }

            $slug .= $entity->getId();

            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($slug);
            $entity->setSlug($slug);
        }

        if ($entity instanceof User) {
            if ($entity->getNewPassword() != null) {
                $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getNewPassword());
                $entity->setPassword($encoded);
            }
        }
    }
}