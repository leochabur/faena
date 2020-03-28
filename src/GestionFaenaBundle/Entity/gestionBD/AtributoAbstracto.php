<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * AtributoAbstracto
 *
 * @ORM\Table(name="sp_gst_atr_abst")
  * @UniqueEntity("atributo", message="Atributo existente en la base de datos!!")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\AtributoAbstractoRepository")
 */
class AtributoAbstracto
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
     * @ORM\Column(name="atributo", type="string", length=255, unique=true)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $atributo;

    /**
     * @ORM\OneToMany(targetEntity="Atributo", mappedBy="atributoAbstracto")
     */
    private $atributos;

    
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
     * Set atributo
     *
     * @param string $atributo
     *
     * @return AtributoAbstracto
     */
    public function setAtributo($atributo)
    {
        $this->atributo = $atributo;

        return $this;
    }

    /**
     * Get atributo
     *
     * @return string
     */
    public function getAtributo()
    {
        return $this->atributo;
    }

    public function __toString()
    {
        return $this->atributo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->atributos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Atributo $atributo
     *
     * @return AtributoAbstracto
     */
    public function addAtributo(\GestionFaenaBundle\Entity\gestionBD\Atributo $atributo)
    {
        $this->atributos[] = $atributo;

        return $this;
    }

    /**
     * Remove atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Atributo $atributo
     */
    public function removeAtributo(\GestionFaenaBundle\Entity\gestionBD\Atributo $atributo)
    {
        $this->atributos->removeElement($atributo);
    }

    /**
     * Get atributos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAtributos()
    {
        return $this->atributos;
    }
}
