<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReservation", type="datetime")
     */
    private $dateReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="text")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Billet", mappedBy="reservation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $billets;

    /**
     * @var bool
     *
     * @ORM\Column(name="demiJournee", type="boolean")
     */
    private $demiJournee;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=2, scale=0)
     */
    private $prix;

    /**
     * @var bool
     *
     * @ORM\Column(name="payer", type="boolean")
     */
    private $payer;

    /**
     * @var string
     *
     * @ORM\Column(name="codeReservation", type="string", length=255, unique=true)
     */
    private $codeReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15)
     */
    private $ip;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->billets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->demiJournee = false;
        $this->payer = false;
        $this->codeReservation = uniqid('CODE_RESA_');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     *
     * @return Reservation
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set billets
     *
     * @param string $billets
     *
     * @return Reservation
     */
    public function setBillets($billets)
    {
        $this->billets = $billets;

        return $this;
    }

    /**
     * Get billets
     *
     * @return string
     */
    public function getBillets()
    {
        return $this->billets;
    }

    /**
     * Set demiJournee
     *
     * @param boolean $demiJournee
     *
     * @return Reservation
     */
    public function setDemiJournee($demiJournee)
    {
        $this->demiJournee = $demiJournee;

        return $this;
    }

    /**
     * Get demiJournee
     *
     * @return bool
     */
    public function getDemiJournee()
    {
        return $this->demiJournee;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Reservation
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set payer
     *
     * @param boolean $payer
     *
     * @return Reservation
     */
    public function setPayer($payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return bool
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Set codeReservation
     *
     * @param string $codeReservation
     *
     * @return Reservation
     */
    public function setCodeReservation($codeReservation)
    {
        $this->codeReservation = $codeReservation;

        return $this;
    }

    /**
     * Get codeReservation
     *
     * @return string
     */
    public function getCodeReservation()
    {
        return $this->codeReservation;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Reservation
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Add billet
     *
     * @param \AppBundle\Entity\Billet $billet
     *
     * @return Reservation
     */
    public function addBillet(\AppBundle\Entity\Billet $billet)
    {
        $this->billets[] = $billet;

        return $this;
    }

    /**
     * Remove billet
     *
     * @param \AppBundle\Entity\Billet $billet
     */
    public function removeBillet(\AppBundle\Entity\Billet $billet)
    {
        $this->billets->removeElement($billet);
    }
}
