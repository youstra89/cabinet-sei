<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * Rdv
 * @ORM\Table(name="rdv")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RdvRepository")
 */
class Rdv
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_rdv", type="date")
     */
    private $date_rdv;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $heure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\Column(type="boolean", length=255)
     */
    private $tenue_rdv = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $heure_arrivee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_save;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_update;



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

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getHeure()
    {
        return $this->heure;
    }

    public function setHeure(string $heure)
    {
        $this->heure = $heure;

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

    public function getTenueRdv()
    {
        return $this->tenueRdv;
    }

    public function setTenueRdv(string $tenue_rdv)
    {
        $this->tenue_rdv = $tenue_rdv;

        return $this;
    }

    public function getHeureArrivee()
    {
        return $this->heure_arrivee;
    }

    public function setHeureArrivee(string $heure_arrivee)
    {
        $this->heure_arrivee = $heure_arrivee;

        return $this;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire)
    {
        $this->commentaire = $commentaire;

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

    public function setDateRdv($date_rdv)
    {
        $this->date_rdv = $date_rdv;

        return $this;
    }

    public function getDateRdv()
    {
        return $this->date_rdv;
    }
}
