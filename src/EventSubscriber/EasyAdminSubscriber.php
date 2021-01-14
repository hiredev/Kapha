<?php

namespace App\EventSubscriber;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\User;
use App\Entity\Lesson;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $authorizationChecker;
    private $tokenStorage;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker, 
        TokenStorageInterface $tokenStorage){
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['crearUserRelacionado'],
            BeforeEntityUpdatedEvent::class => ['actualizandoUser'],
            BeforeCrudActionEvent::class => ['verificarPermisos']
        ];
    }

    public function crearUserRelacionado(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        $user = $this->tokenStorage->getToken()->getUser();

        if (($entity instanceof Teacher)) {
            $user = $entity->getUser();
            if(!$user){
                $user = new User();
                $entity->setUser($user);
                $user->setEmail('xxxx'.rand().'@teacher.com');                
            }
            $user->setRoles(array('ROLE_USER','ROLE_TEACHER'));
        }

        if (($entity instanceof Student)) {
            $user = $entity->getUser();
            if(!$user){
                $user = new User();
                $entity->setUser($user);
                $user->setEmail('xxxx'.rand().'@teacher.com');                
            }
            $user->setRoles(array('ROLE_USER'));
        }

        if (($entity instanceof Lesson)) {
            if ($this->authorizationChecker->isGranted('ROLE_TEACHER') && !$this->authorizationChecker->isGranted('ROLE_ADMIN') && !$this->authorizationChecker->isGranted('ROLE_MODERATOR')){
                $entity->setTeacher($user->getTeacher());
            }
        }
    }

    public function actualizandoUser(BeforeEntityUpdatedEvent $event){
        $entity = $event->getEntityInstance();
        if (($entity instanceof User)) {
            $newPassword = $entity->getNewPassword();
            if($newPassword){
                $entity->setPassword($newPassword);
            }        
        }
    }

    public function verificarPermisos(BeforeCrudActionEvent $event){
        $user = $this->tokenStorage->getToken()->getUser();

        if(!$user){
            throw new AccessDeniedException();
        }

        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN') && !$this->authorizationChecker->isGranted('ROLE_MODERATOR')) {
            $context = $event->getAdminContext();
            $entity = $context->getEntity();
            if($entity){
                $instance = $entity->getInstance();

                if($instance instanceof Teacher){
                    if($instance->getUser()->getId() != $user->getId()){
                        dd($instance);
                        throw new AccessDeniedHttpException();
                    }                
                }
                if($instance instanceof User){
                    if($instance->getId() != $user->getId()){
                        throw new AccessDeniedException();
                    }                
                }            

                if($instance instanceof Lesson){
                    if($instance->getTeacher()->getUser()->getId() != $user->getId()){
                        throw new AccessDeniedException();
                    }
                }
            }
        }
    }
}