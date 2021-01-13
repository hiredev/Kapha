<?php

namespace App\Security;

use App\Entity\Usuario;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;


class MyEntityUserProvider extends EntityUserProvider implements AccountConnectorInterface{

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        $serviceName = $response->getResourceOwner()->getName();
        $setterId = 'set'. ucfirst($serviceName) . 'ID';
        $setterAccessToken = 'set'. ucfirst($serviceName) . 'AccessToken';

        // unique integer
        $username = $response->getUsername();
        if (null === $usuario = $this->findUser(array($this->properties[$resourceOwnerName] => $username))) {
            // TODO: Create the usuario
            $usuario = new Usuario();

            $usuario->setEmail($response->getEmail());
            $usuario->setRoles(array('ROLE_USER'));

            $usuario->$setterId($username);
            $usuario->$setterAccessToken($response->getAccessToken());

            $this->em->persist($usuario);
            $this->em->flush();

            return $usuario;
        }
        // JUST FOR FACEBOOK
        $usuario->setFacebookAccessToken($response->getAccessToken());

        return $usuario;
    }

    /**
     * Connects the response to the usuario object.
     *
     * @param UserInterface $usuario The usuario object
     * @param UserResponseInterface $response The oauth response
     */
    public function connect(UserInterface $usuario, UserResponseInterface $response)
    {
        if (!$usuario instanceof Usuario) {
            throw new UnsupportedUserException(sprintf('Expected an instance of App\Model\User, but got "%s".', get_class($usuario)));
        }

        $property = $this->getProperty($response);
        $username = $response->getUsername();

        if (null !== $previousUsuario = $this->registry->getRepository(Usuario::class)->findOneBy(array($property => $username))) {
            // 'disconnect' previously connected users
            $this->disconnect($previousUsuario, $response);
        }


        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set'. ucfirst($serviceName) . 'AccessToken';

        $usuario->$setter($response->getAccessToken());

        $this->updateUser($usuario, $response);
    }

    /**
     * ##STOLEN#
     * Gets the property for the response.
     *
     * @param UserResponseInterface $response
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getProperty(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        return $this->properties[$resourceOwnerName];
    }

    /**
     * Disconnects a user.
     *
     * @param UserInterface $usuario
     * @param UserResponseInterface $response
     * @throws \TypeError
     */
    public function disconnect(UserInterface $usuario, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $accessor = PropertyAccess::createPropertyAccessor();

        $accessor->setValue($usuario, $property, null);

        $this->updateUsuario($usuario, $response);
    }

    /**
     * Update the user and persist the changes to the database.
     * @param UserInterface $usuario
     * @param UserResponseInterface $response
     */
    private function updateUser(UserInterface $usuario, UserResponseInterface $response)
    {
        $usuario->setEmail($response->getEmail());

        $this->em->persist($usuario);
        $this->em->flush();
    }
}