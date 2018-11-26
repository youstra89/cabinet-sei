<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string")
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="pnom", type="string")
     */
    protected $pnom;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string")
     */
    protected $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="filiere", type="string")
     */
    protected $filiere;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string")
     */
    protected $level;

    /**
     * @var string
     *
     * @ORM\Column(name="school", type="string")
     */
    protected $school;


    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set pnom
     *
     * @param string $pnom
     *
     * @return User
     */
    public function setPnom($pnom)
    {
        $this->pnom = $pnom;

        return $this;
    }

    /**
     * Get pnom
     *
     * @return string
     */
    public function getPnom()
    {
        return $this->pnom;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return User
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set filiere
     *
     * @param string $filiere
     *
     * @return User
     */
    public function setFiliere($filiere)
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * Get filiere
     *
     * @return string
     */
    public function getFiliere()
    {
        return $this->filiere;
    }

    /**
     * Set level
     *
     * @param string $level
     *
     * @return User
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set school
     *
     * @param string $school
     *
     * @return User
     */
    public function setSchool($school)
    {
        $this->level = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return string
     */
    public function getSchool()
    {
        return $this->school;
    }
}
