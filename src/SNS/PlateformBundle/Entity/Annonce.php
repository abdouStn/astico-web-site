<?php

namespace SNS\PlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Annonce
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SNS\PlateformBundle\Entity\AnnonceRepository")
 * 
 * Cette derniere ligne doit etre rajouter si le repository n'est pas généré automatiquement par le système, 
 *      pour prendre en compte les methodes qui y sont definies.
 */
class Annonce
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

	/**
     * @ORM\ManyToOne(targetEntity="SNS\MembreBundle\Entity\Membre", cascade={"persist"})
     * @Assert\Valid()
     */
    private $auteur;
    
    /***  cet attribut n'est plus pris en compte.
     * @ORM\OneToOne(targetEntity="SNS\PlateformBundle\Entity\Photo", cascade={"persist", "remove"})
     * //@Assert\Valid()
     */
    //private $photo;

    
    public function __construct()
    {
    	 $this->date = new \Datetime();    	 
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Annonce
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Annonce
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Annonce
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
     * Set auteur
     *
     * @param \SNS\MembreBundle\Entity\Membre $auteur
     * @return Annonce
     */
    public function setAuteur(\SNS\MembreBundle\Entity\Membre $auteur = null)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \SNS\MembreBundle\Entity\Membre 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set photo
     
     * @param \SNS\PlateformBundle\Entity\Photo $photo
     * @return Annonce
     *
    public function setPhoto(\SNS\PlateformBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \SNS\PlateformBundle\Entity\Photo 
     *
    public function getPhoto()
    {
        return $this->photo;
    }*/
}
