<?php

namespace SNS\PlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Stage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SNS\PlateformBundle\Entity\StageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Stage
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
     * @var string
     *
     * @ORM\Column(name="intutile", type="string", length=255)
     */
    private $intutile;

    /**
     * @var \Date
     *
     * @ORM\Column(name="date_debut", type="date")
     */
    private $dateDebut;
     
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_soumission", type="date")
     */
     private $dateSoumission;
    /**
     * @var integer
     *
     * @ORM\Column(name="duree", type="integer")
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="text")
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="contacte", type="text")
     */
    private $contacte;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="parcours", type="string", length=255)
     */
    private $parcours;

   /**
     * @ORM\ManyToOne(targetEntity="SNS\MembreBundle\Entity\Membre", inversedBy="stages", cascade={"persist"})
     * @Assert\Valid()
     */
    private $auteur;

    /**
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename="vide";
    
    private $file;	

    // On ajoute cet attribut pour y stocker le nom du fichier temporairement
    private $tempFilename; 

    public function __construct()
    {
    	 $this->dateDebut = new \Datetime();
    	 $this->dateSoumission = new \Datetime();
    	 //$this->filename = "";
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
     * Set intutile
     *
     * @param string $intutile
     * @return Stage
     */
    public function setIntutile($intutile)
    {
        $this->intutile = $intutile;

        return $this;
    }

    /**
     * Get intutile
     *
     * @return string 
     */
    public function getIntutile()
    {
        return $this->intutile;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Stage
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
     * Set duree
     *
     * @param integer $duree
     * @return Stage
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer 
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Stage
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
     * Set contacte
     *
     * @param string $contacte
     * @return Stage
     */
    public function setContacte($contacte)
    {
        $this->contacte = $contacte;

        return $this;
    }

    /**
     * Get contacte
     *
     * @return string 
     */
    public function getContacte()
    {
        return $this->contacte;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Stage
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
     * Set parcours
     *
     * @param string $parcours
     * @return Stage
     */
    public function setParcours($parcours)
    {
        $this->parcours = $parcours;

        return $this;
    }

    /**
     * Get parcours
     *
     * @return string 
     */
    public function getParcours()
    {
        return $this->parcours;
    }

    /**
     * Set auteur
     *
     * @param \SNS\MembreBundle\Entity\Membre $auteur
     * @return Stage
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
     * Set dateSoumission
     *
     * @param \DateTime $dateSoumission
     * @return Stage
     */
    public function setDateSoumission($dateSoumission)
    {
        $this->dateSoumission = $dateSoumission;

        return $this;
    }

    /**
     * Get dateSoumission
     *
     * @return \DateTime 
     */
    public function getDateSoumission()
    {
        return $this->dateSoumission;
    }
    
    /**
     * Set filename
     *
     * @param string $filename
     * @return Stage
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }
    
    
    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
  public function setFile(UploadedFile $file)
  {
    $this->file = $file;

    // On vérifie si on avait déjà un fichier pour cette entité
    if (null !== $this->filename) {
      // On sauvegarde le nom du fichier pour le supprimer plus tard
      $this->tempFilename = $this->getId().$this->filename;

      // On réinitialise les valeurs des attributs
      $this->filename = null;
      
      
    }
  }
  
  public function getFile()
  {
    return $this->file;
  }

 
  /**
   * @ORM\PrePersist()
   * @ORM\PreUpdate()
   */
  public function preUpload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif)
    if (null === $this->file) {
      return;
    }
	
    // Le nom du fichier est son id concatener au nom original
    $this->filename = $this->file->getClientOriginalName() ; 

  }

  /**
   * @ORM\PostPersist()
   * @ORM\PostUpdate()
   */
  public function upload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif)
    if (null === $this->file) {
      return;
    }

    // Si on avait un ancien fichier, on le supprime
    if (null !== $this->tempFilename) {
      $oldFile = $this->getUploadRootDir().'/'.$this->tempFilename;
      if (file_exists($oldFile)) {
        unlink($oldFile);
      }
    }
     
     $this->filename = $this->getId().$this->file->getClientOriginalName();
    // On déplace le fichier envoyé dans le répertoire de notre choix
    $this->file->move(
      $this->getUploadRootDir(), // Le répertoire de destination
      $this->filename   // Le nom du fichier à créer, ici « id.extension »
    );
  }

  /**
   * @ORM\PreRemove()
   */
  public function preRemoveUpload()
  {
    // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
    $this->tempFilename = $this->getUploadRootDir().'/'.$this->getId().$this->filename;
  }

  /**
   * @ORM\PostRemove()
   */
  public function removeUpload()
  {
    // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
    if (file_exists($this->tempFilename)) {
      // On supprime le fichier
      unlink($this->tempFilename);
    }
  }

  public function getUploadDir()
  {
    // On retourne le chemin relatif vers l'image pour un navigateur
    return 'uploads/stages';
  }

  protected function getUploadRootDir()
  {
    // On retourne le chemin relatif vers l'image pour notre code PHP
    return __DIR__.'/../../../../web/'.$this->getUploadDir();
  }
  
  public function getWebPath()
  {
    return $this->getUploadDir().'/'.$this->getId().$this->filename;
  }
}
