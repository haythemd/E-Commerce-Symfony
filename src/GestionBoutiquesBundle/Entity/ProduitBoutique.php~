<?php

namespace GestionBoutiquesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProduitBoutique
 *
 * @ORM\Table(name="produit_boutique")
 * @ORM\Entity(repositoryClass="GestionBoutiquesBundle\Repository\ProduitBoutiqueRepository")
 *
 */
class ProduitBoutique
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
     * @ORM\Column(name="nomProduit", type="string", length=255)
     */
    private $nomProduit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDepot", type="date")
     */
    private $dateDepot;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    //Attributs ajoutés manuellement:

    /**
     * @ORM\ManyToOne(targetEntity="GestionBoutiquesBundle\Entity\Boutique", inversedBy="produits")
     * @ORM\JoinColumn(name="boutique_id", referencedColumnName="id")
     */
    private $boutique;

    /**
     * @ORM\ManyToOne(targetEntity="GestionBoutiquesBundle\Entity\Categorie", inversedBy="produits")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="please upload ")
     * @Assert\Image()
     */

    private $image;

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
     * Get nomProduit
     *
     * @return string
     */
    public function getNomProduit()
    {
        return $this->nomProduit;
    }

    /**
     * Set nomProduit
     *
     * @param string $nomProduit
     *
     * @return ProduitBoutique
     */
    public function setNomProduit($nomProduit)
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    /**
     * Get dateDepot
     *
     * @return \DateTime
     */
    public function getDateDepot()
    {
        return $this->dateDepot;
    }

    /**
     * Set dateDepot
     *
     * @param \DateTime $dateDepot
     *
     * @return ProduitBoutique
     */
    public function setDateDepot($dateDepot)
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return ProduitBoutique
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

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
     * Set boutique
     *
     * @param \GestionBoutiquesBundle\Entity\Boutique $boutique
     *
     * @return ProduitBoutique
     */
    public function setBoutique(\GestionBoutiquesBundle\Entity\Boutique $boutique = null)
    {
        $this->boutique = $boutique;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \GestionBoutiquesBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set categorie
     *
     * @param \GestionBoutiquesBundle\Entity\Categorie $categorie
     *
     * @return ProduitBoutique
     */
    public function setCategorie(\GestionBoutiquesBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

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
     * Set description
     *
     * @param string $description
     *
     * @return ProduitBoutique
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }


    /**
     * Set image
     *
     * @param string $image
     *
     * @return ProduitBoutique
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
