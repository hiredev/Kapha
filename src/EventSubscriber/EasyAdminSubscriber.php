<?php

namespace App\EventSubscriber;

use App\Entity\Alumno;
use App\Entity\Maestro;
use App\Entity\Usuario;
use App\Entity\Aula;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
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
            BeforeEntityPersistedEvent::class => ['crearUsuarioRelacionado'],
            BeforeEntityUpdatedEvent::class => ['actualizandoUsuario'],
            BeforeCrudActionEvent::class => ['verificarPermisos']
        ];
    }

    public function crearUsuarioRelacionado(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        $usuario = $this->tokenStorage->getToken()->getUser();

        if (($entity instanceof Maestro)) {
            $usuario = $entity->getUsuario();
            if(!$usuario){
                $usuario = new Usuario();
                $entity->setUsuario($usuario);
                $usuario->setEmail('xxxx'.rand().'@maestro.com');                
            }
            $usuario->setRoles(array('ROLE_USER','ROLE_MAESTRO'));
        }

        if (($entity instanceof Alumno)) {
            $usuario = $entity->getUsuario();
            if(!$usuario){
                $usuario = new Usuario();
                $entity->setUsuario($usuario);
                $usuario->setEmail('xxxx'.rand().'@maestro.com');                
            }
            $usuario->setRoles(array('ROLE_USER'));
        }

        if (($entity instanceof Aula)) {            
            if ($this->authorizationChecker->isGranted('ROLE_MAESTRO') && !$this->authorizationChecker->isGranted('ROLE_ADMIN') && !$this->authorizationChecker->isGranted('ROLE_MODERADOR')){
                $entity->setMaestro($usuario->getMaestro());
            }
        }
    }

    public function actualizandoUsuario(BeforeEntityUpdatedEvent $event){
        $entity = $event->getEntityInstance();
        if (($entity instanceof Usuario)) {
            $newPassword = $entity->getNewPassword();
            if($newPassword){
                $entity->setPassword($newPassword);
            }        
        }
    }

    public function verificarPermisos(BeforeCrudActionEvent $event){
        $usuario = $this->tokenStorage->getToken()->getUser();

        if(!$usuario){
            throw new AccessDeniedException();
        }

        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN') && !$this->authorizationChecker->isGranted('ROLE_MODERADOR')) {
            $context = $event->getAdminContext();
            $entity = $context->getEntity();
            if($entity){
                $instance = $entity->getInstance();

                if($instance instanceof Maestro){
                    if($instance->getUsuario()->getId() != $usuario->getId()){
                        dd($instance);
                        throw new AccessDeniedException();
                    }                
                }
                if($instance instanceof Usuario){
                    if($instance->getId() != $usuario->getId()){
                        throw new AccessDeniedException();
                    }                
                }            

                if($instance instanceof Aula){
                    if($instance->getMaestro()->getUsuario()->getId() != $usuario->getId()){
                        throw new AccessDeniedException();
                    }
                }
            }
        }
    }
}