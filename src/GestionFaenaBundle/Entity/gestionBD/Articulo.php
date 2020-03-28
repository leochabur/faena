<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Articulo
 *
 * @ORM\Table(name="sp_gst_art")
 * @UniqueEntity("nombre", message="Articulo existente en la base de datos")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\ArticuloRepository")
 */
class Articulo
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
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="ArticuloAtributoConcepto", mappedBy="articulo")
     */
    private $artsAtrConc;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Articulo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    public function __toString()
    {
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->artsAtrConc = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add artsAtrConc
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artsAtrConc
     *
     * @return Articulo
     */
    public function addArtsAtrConc(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artsAtrConc)
    {
        $this->artsAtrConc[] = $artsAtrConc;

        return $this;
    }

    /**
     * Remove artsAtrConc
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artsAtrConc
     */
    public function removeArtsAtrConc(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artsAtrConc)
    {
        $this->artsAtrConc->removeElement($artsAtrConc);
    }

    /**
     * Get artsAtrConc
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArtsAtrConc()
    {
        return $this->artsAtrConc;
    }
}
