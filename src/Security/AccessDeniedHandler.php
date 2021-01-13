<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



class AccessDeniedHandler implements AccessDeniedHandlerInterface
{    
    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $roles = $this->security->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $roles)){
            return new RedirectResponse($this->urlGenerator->generate('admin'));            
        }        

        if (in_array('ROLE_MODERADOR', $roles)){
            return new RedirectResponse($this->urlGenerator->generate('moderador'));            
        }

        if (in_array('ROLE_MAESTRO', $roles)){
            return new RedirectResponse($this->urlGenerator->generate('maestro'));            
        }

        return new RedirectResponse($this->urlGenerator->generate('default'));
    }
}