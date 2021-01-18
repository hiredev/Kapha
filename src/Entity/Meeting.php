<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use App\Repository\MeetingRepository;

/**
 * @ORM\Entity(repositoryClass=MeetingRepository::class)
 */
class Meeting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lesson")
     */
    private $lesson;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     */
    private $zoomLink;

    /**
     * @ORM\Column(type="string")
     */
    private $zoomPassword;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * @param mixed $lesson
     * @return Meeting
     */
    public function setLesson($lesson)
    {
        $this->lesson = $lesson;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return Meeting
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZoomLink()
    {
        return $this->zoomLink;
    }

    /**
     * @param mixed $zoomLink
     * @return Meeting
     */
    public function setZoomLink($zoomLink)
    {
        $this->zoomLink = $zoomLink;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZoomPassword()
    {
        return $this->zoomPassword;
    }

    /**
     * @param mixed $zoomPassword
     * @return Meeting
     */
    public function setZoomPassword($zoomPassword)
    {
        $this->zoomPassword = $zoomPassword;
        return $this;
    }


}
