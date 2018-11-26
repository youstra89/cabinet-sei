<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * Message
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_save;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_update;

    /**
     * @ORM\Column(type="datetime")
     */
    private $read_at;



    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getFullname()
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact(string $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    public function getDateSave()
    {
        return $this->date_save;
    }

    public function setDateSave(\DateTimeInterface $date_save)
    {
        $this->date_save = $date_save;

        return $this;
    }

    public function getDateUpdate()
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update)
    {
        $this->date_update = $date_update;

        return $this;
    }

    public function getReadAt()
    {
        return $this->read_at;
    }

    public function setReadAt(\DateTimeInterface $read_at)
    {
        $this->read_at = $read_at;

        return $this;
    }
}
