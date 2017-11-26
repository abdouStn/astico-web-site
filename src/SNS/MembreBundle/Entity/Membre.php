<?php

namespace SNS\MembreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;     // cette annotation se definit sur une classe et non sur un attribut

use FOS\UserBundle\Model\User as BaseUser;



/**
 * Membre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SNS\MembreBundle\Entity\MembreRepository")
 */
class Membre extends BaseUser //plus besoin de  implements UserInterface car baseuser l'implemente // pour automatiser certaines taches     , implementer l'interface apres la generation des getters
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

/*
fosUserBundle contient deja username, password, email, ...
*/


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anniversaire", type="date")
     */
    private $anniversaire;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_bureau", type="boolean")
     */
    private $isBureau;  // si le membre fait partir du bureau
    
    /**
     * @var string
     *
     * @ORM\Column(name="poste", type="string", length=255)
     */
    private $poste;   // poste dans l'asso
    
    /**
     * @ORM\OneToOne(targetEntity="SNS\PlateformBundle\Entity\Photo", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=255)
     */
    private $specialite;
	
	//nullable=true
    
    /**
     * @ORM\OneToMany(targetEntity="SNS\PlateformBundle\Entity\Stage", mappedBy="auteur", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $stages;
	
    public function __construct()
    {
        parent::__construct();
    
    	 $this->anniversaire = new \Datetime();
    	 $this->isBureau = false;
    	 $this->poste = "Membre";
    	 $this->photo = null;
    	 //$this->nom = "inconnu";
    	 //$this->prenom = "inconnu"; 
    	 //$this->specialite = "inconnu";   	 
    	 /*$this->salt = " ";    	 
    	 $this->locked = false;    	 
    	 $this->expired = false;    	 //credentials_expired
    	 $this->credentialsExpired = false;
    	 $this->roles = array('ROLE_USER'); //role ROLE_USER qui est le role de base*/
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
     * Set nom
     *
     * @param string $nom
     * @return Membre
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
     * Set prenom
     *
     * @param string $prenom
     * @return Membre
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    

    /**
     * Set anniversaire
     *
     * @param string $anniversaire
     * @return Membre
     */
    public function setAnniversaire($anniversaire)
    {
        $this->anniversaire = $anniversaire;

        return $this;
    }

    /**
     * Get anniversaire
     *
     * @return string 
     */
    public function getAnniversaire()
    {
        return $this->anniversaire;
    }

    
    /**
     * Set specialite
     *
     * @param string $specialite
     * @return Membre
     */
    public function setSpecialite($specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return string 
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set photo
     *
     * @param \SNS\PlateformBundle\Entity\Photo $photo
     * @return Membre
     */
    public function setPhoto(\SNS\PlateformBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \SNS\PlateformBundle\Entity\Photo 
     */
    public function getPhoto()
    {
        return $this->photo;
    }
    
    public function addStage(\SNS\PlateformBundle\Entity\Stage $stage)
    {
        $this->stages[] = $application;

        $stage->setAuteur($this);
        return $this;
    }

    public function removeStage(\SNS\PlateformBundle\Entity\Stage $stage)
    {
        $this->stages->removeElement($stage);

         $stage->setAuteur(null);
    }

    /**
     * Get stages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStages()
    {
        return $this->stages;
    }


    /**
     * Set poste
     *
     * @param string $poste
     * @return Membre
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * Get poste
     *
     * @return string 
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Membre
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set isInBureau
     *
     * @param boolean $isInBureau
     * @return Membre
     */
    public function setIsInBureau($isInBureau)
    {
        $this->isInBureau = $isInBureau;

        return $this;
    }

    /**
     * Get isInBureau
     *
     * @return boolean 
     */
    public function getIsInBureau()
    {
        return $this->isInBureau;
    }

    /**
     * Set inBureau
     *
     * @param boolean $inBureau
     * @return Membre
     */
    public function setInBureau($inBureau)
    {
        $this->inBureau = $inBureau;

        return $this;
    }

    /**
     * Get inBureau
     *
     * @return boolean 
     */
    public function getInBureau()
    {
        return $this->inBureau;
    }

    /**
     * Set isBureau
     *
     * @param boolean $isBureau
     * @return Membre
     */
    public function setIsBureau($isBureau)
    {
        $this->isBureau = $isBureau;

        return $this;
    }

    /**
     * Get isBureau
     *
     * @return boolean 
     */
    public function getIsBureau()
    {
        return $this->isBureau;
    }
}
