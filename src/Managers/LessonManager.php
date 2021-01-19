<?php


namespace App\Managers;


use App\Entity\Lesson;
use App\Repository\CuentaZoomRepository;
use Doctrine\ORM\EntityManagerInterface;

class LessonManager
{
    private $zoom;
    private $entityManager;

    public function __construct(EntityManagerInterface $manager, ZoomManager $zoom)
    {
        $this->zoom = $zoom;
        $this->entityManager = $manager;
    }

    public function createZoomMeeting(Lesson $lesson)
    {
        $zoom = $this->entityManager->getRepository("App:CuentaZoom")->find(1);
        $lesson->setPassword($this->generatePassword());
        $this->zoom->createMeeting($zoom, $lesson);


        return $lesson;
    }


    private function generatePassword()
    {
        return rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10);
    }

}