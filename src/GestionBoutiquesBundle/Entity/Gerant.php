<?php

namespace GestionBoutiquesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gerant
 *
 * @ORM\Table(name="gerant")
 * @ORM\Entity(repositoryClass="GestionBoutiquesBundle\Repository\GerantRepository")
 */
class Gerant
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
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $dateDebut;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;

    //Attributs ajoutés manuellement:
    //Un gérant n'est assoscié qu'a une seule boutique
    /**
     *
     * @ORM\OneToOne(targetEntity="GestionBoutiquesBundle\Entity\Boutique", inversedBy="gerant")
     * @ORM\JoinColumn(name="boutique_id", referencedColumnName="id")
     */
    private $boutique;

    /**
     *
     * @ORM\OneToOne(targetEntity="TestBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Gerant
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Gerant
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return bool
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set boutique
     *
     * @param \GestionBoutiquesBundle\Entity\Boutique $boutique
     *
     * @return Gerant
     */
    public function setBoutique(\GestionBoutiquesBundle\Entity\Boutique $boutique = null)
    {
        $this->boutique = $boutique;

        return $this;
    }

    /**
     * Get boutique
     *
     * @return \GestionBoutiquesBundle\Entity\Boutique
     */
    public function getBoutique()
    {
        return $this->boutique;
    }

    /**
     * Set user
     *
     * @param \TestBundle\Entity\User $user
     *
     * @return Gerant
     */
    public function setUser(\TestBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TestBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
