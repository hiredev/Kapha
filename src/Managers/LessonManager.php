<?php


namespace App\Managers;

use App\Entity\Lesson;
use App\Repository\CuentaZoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LessonManager
{
    private $router;
    private $zoom;
    private $container;
    private $entityManager;

    public function __construct(RouterInterface $router, EntityManagerInterface $manager, ZoomManager $zoom)
    {
//        $this->container = $container;
        $this->router = $router;
        $this->zoom = $zoom;
        $this->entityManager = $manager;
    }

    public function createZoomMeeting(Lesson $lesson)
    {
        $zoom = $this->entityManager->getRepository("App:CuentaZoom")->findBy([], [
            'id' => 'DESC'
        ], 1)[0];
        if (!$zoom) {
            return new RedirectResponse($this->router->generate("zoom_create"));
        }

        $lesson->setPassword($this->generatePassword());
        $this->zoom->createMeeting($zoom, $lesson);


        return $lesson;
    }


    private function generatePassword()
    {
        return rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10);
    }

}