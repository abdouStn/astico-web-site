<?php

namespace SNS\PlateformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 *
 * @ORM\Entity(repositoryClass="SNS\PlateformBundle\Entity\DocumentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Document
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
     * @ORM\Column(name="descriptif", type="string", length=255)
     */
    private $descriptif;

    /**
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;
	
    /**
     * @ORM\ManyToOne(targetEntity="SNS\MembreBundle\Entity\Membre", cascade={"persist"})
     * @Assert\Valid()
     */
    private $auteur;
	
    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;
    
    /**
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;
    
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the document.")
     * @Assert\File(mimeTypes={ "application/pdf","text/plain","application/vnd.openxmlformats-officedocument.wordprocessingml.document" }) 
     */
    private $file;	

    // On ajoute cet attribut pour y stocker le nom du fichier temporairement
    private $tempFilename; 
	
    public function __construct()
    {
    	 $this->date = new \Datetime();
    	 $this->published = true;
    	 //$this->filename  = $this->file->getClientOriginalName();
    	 
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
     * Set descriptif
     *
     * @param string $descriptif
     * @return Document
     */
    public function setDescriptif($descriptif)
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * Get descriptif
     *
     * @return string 
     */
    public function getDescriptif()
    {
        return $this->descriptif;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Document
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
     * Set auteur
     *
     * @param \SNS\MembreBundle\Entity\Membre $auteur
     * @return Document
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
     * Set published
     *
     * @param boolean $published
     * @return Document
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     *
     * @param string $filename
     * @return Document
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }
    
    public function getFile()
    {
    return $this->file;
    }

    	 // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
  public function setFile($file)
  {
    $this->file = $file;

    // On vérifie si on avait déjà un fichier pour cette entité
    if (null !== $this->filename) {
      // On sauvegarde le nom du fichier pour le supprimer plus tard
      $this->tempFilename = $this->getId().$this->filename;

      // On réinitialise les valeurs des attributs
      $this->filename = null;
      
      
    }
    return $this;
  }
  
  
  
  
  
      /* si l'on utilise pas les evenements
      public function upload()
	  {
	    // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
	    if (null === $this->file) {
		return;
	    }

	    // On récupère le nom original du fichier de l'internaute
	    $name = $this->file->getClientOriginalName();

	    // On déplace le fichier envoyé dans le répertoire de notre choix
	    $this->file->move($this->getUploadRootDir(), $name);

	    // On sauvegarde le nom de fichier dans notre attribut $url
	    $this->url = $name;

	    // On crée également le futur attribut alt de notre balise <img>
	    $this->alt = $name;
	  }
	*/

  

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
    return 'uploads/documents';
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
