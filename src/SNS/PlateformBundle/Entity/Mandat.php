<?php

namespace SNS\PlateformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;     // cette annotation se definit sur une classe et non sur un attribut

/**
 * Mandat
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SNS\PlateformBundle\Entity\MandatRepository")
 * @UniqueEntity(fields="annee", message="Ce mandat existe déjà avec ce titre.")
 */
class Mandat
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
     * @ORM\Column(name="annee", type="string", length=20, unique=true)
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="programme", type="string", length=255)
     */
    private $programme;

    /**
     * @var string
     *
     * @ORM\Column(name="bilan", type="text")
     */
    private $bilan;

    /**
     * @ORM\OneToMany(targetEntity="SNS\MembreBundle\Entity\Membre", mappedBy="mandat")
     */
    private $bureau;
    
    /**
     * @ORM\OneToMany(targetEntity="SNS\MembreBundle\Entity\Membre", mappedBy="mandat")
     */
    private $membres;

    
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
     * Set annee
     *
     * @param string $annee
     * @return Mandat
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set programme
     *
     * @param string $programme
     * @return Mandat
     */
    public function setProgramme($programme)
    {
        $this->programme = $programme;

        return $this;
    }

    /**
     * Get programme
     *
     * @return string 
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     * Set bilan
     *
     * @param string $bilan
     * @return Mandat
     */
    public function setBilan($bilan)
    {
        $this->bilan = $bilan;

        return $this;
    }

    /**
     * Get bilan
     *
     * @return string 
     */
    public function getBilan()
    {
        return $this->bilan;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bureau = new \Doctrine\Common\Collections\ArrayCollection();
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bureau
     *
     * @param \SNS\MembreBundle\Entity\Membre $bureau
     * @return Mandat
     */
    public function addBureau(\SNS\MembreBundle\Entity\Membre $bureau)
    {
        $this->bureau[] = $bureau;

        return $this;
    }

    /**
     * Remove bureau
     *
     * @param \SNS\MembreBundle\Entity\Membre $bureau
     */
    public function removeBureau(\SNS\MembreBundle\Entity\Membre $bureau)
    {
        $this->bureau->removeElement($bureau);
    }

    /**
     * Get bureau
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBureau()
    {
        return $this->bureau;
    }

    /**
     * Add membres
     *
     * @param \SNS\MembreBundle\Entity\Membre $membres
     * @return Mandat
     */
    public function addMembre(\SNS\MembreBundle\Entity\Membre $membres)
    {
        $this->membres[] = $membres;

        return $this;
    }

    /**
     * Remove membres
     *
     * @param \SNS\MembreBundle\Entity\Membre $membres
     */
    public function removeMembre(\SNS\MembreBundle\Entity\Membre $membres)
    {
        $this->membres->removeElement($membres);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembres()
    {
        return $this->membres;
    }
}
