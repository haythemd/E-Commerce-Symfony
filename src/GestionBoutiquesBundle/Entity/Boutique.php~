<?php

namespace GestionBoutiquesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//use Doctrine\ORM\Mapping\OneToOne;

/**
 * Boutique
 *
 * @ORM\Table(name="boutique")
 * @ORM\Entity(repositoryClass="GestionBoutiquesBundle\Repository\BoutiqueRepository")
 */
class Boutique
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
     * @var string
     *
     * @ORM\Column(name="nomBoutique", type="string", length=255)
     */
    private $nomBoutique;

    /**
     * @var string
     *
     * @ORM\Column(name="Lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCeation", type="date")
     */
    private $dateCeation;

    //Atrributs ajoutés manuellement:

    /**
     *
     * @ORM\OneToOne(targetEntity="GestionBoutiquesBundle\Entity\Gerant", mappedBy="boutique")
     */
    private $gerant;

    /**
     * @ORM\OneToMany(targetEntity="GestionBoutiquesBundle\Entity\ProduitBoutique", mappedBy="boutique")
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
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
     * Set nomBoutique
     *
     * @param string $nomBoutique
     *
     * @return Boutique
     */
    public function setNomBoutique($nomBoutique)
    {
        $this->nomBoutique = $nomBoutique;

        return $this;
    }

    /**
     * Get nomBoutique
     *
     * @return string
     */
    public function getNomBoutique()
    {
        return $this->nomBoutique;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Boutique
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Boutique
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCeation
     *
     * @param string $dateCeation
     *
     * @return Boutique
     */
    public function setDateCeation($dateCeation)
    {
        $this->dateCeation = $dateCeation;

        return $this;
    }

    /**
     * Get dateCeation
     *
     * @return string
     */
    public function getDateCeation()
    {
        return $this->dateCeation;
    }


    /**
     * Add produit
     *
     * @param \GestionBoutiquesBundle\Entity\ProduitBoutique $produit
     *
     * @return Boutique
     */
    public function addProduit(\GestionBoutiquesBundle\Entity\ProduitBoutique $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \GestionBoutiquesBundle\Entity\ProduitBoutique $produit
     */
    public function removeProduit(\GestionBoutiquesBundle\Entity\ProduitBoutique $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Set gerant
     *
     * @param \GestionBoutiquesBundle\Entity\Gerant $gerant
     *
     * @return Boutique
     */
    public function setGerant(\GestionBoutiquesBundle\Entity\Gerant $gerant = null)
    {
        $this->gerant = $gerant;

        return $this;
    }

    /**
     * Get gerant
     *
     * @return \GestionBoutiquesBundle\Entity\Gerant
     */
    public function getGerant()
    {
        return $this->gerant;
    }

}
