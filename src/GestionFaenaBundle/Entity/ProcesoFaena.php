<?php

namespace GestionFaenaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProcesoFaena
 *
 * @ORM\Table(name="sp_proc_fan")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\ProcesoFaenaRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({1:"ProcesoFaena", 2:"ProcesoInicio", 3:"ProcesoMedio", 4:"ProcesoFin"})
 */
abstract class ProcesoFaena
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
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena", mappedBy="proceso")
     */
    private $articulos;


    /**
     * @ORM\ManyToMany(targetEntity="ProcesoFaena", mappedBy="procesosDestino")
     */
    private $procesosOrigen;

    /**
     * @ORM\ManyToMany(targetEntity="ProcesoFaena", inversedBy="procesosOrigen")
     * @ORM\JoinTable(name="sp_proc_join",
     *      joinColumns={@ORM\JoinColumn(name="proccess_sender_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="proccess_receiver_id", referencedColumnName="id")}
     *      )
     */
    private $procesosDestino;


    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true, options={"default":true})
     */
    private $activo; 

    public abstract function getType();

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
     * @return ProcesoFaena
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

    /**
     * Add articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena $articulo
     *
     * @return ProcesoFaena
     */
    public function addArticulo(\GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena $articulo)
    {
        $this->articulos[] = $articulo;

        return $this;
    }

    /**
     * Remove articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena $articulo
     */
    public function removeArticulo(\GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena $articulo)
    {
        $this->articulos->removeElement($articulo);
    }

    /**
     * Get articulos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticulos()
    {
        return $this->articulos;
    }

    /**
     * Add procesosOrigen
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesosOrigen
     *
     * @return ProcesoFaena
     */
    public function addProcesosOrigen(\GestionFaenaBundle\Entity\ProcesoFaena $procesosOrigen)
    {
        $this->procesosOrigen[] = $procesosOrigen;

        return $this;
    }

    /**
     * Remove procesosOrigen
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesosOrigen
     */
    public function removeProcesosOrigen(\GestionFaenaBundle\Entity\ProcesoFaena $procesosOrigen)
    {
        $this->procesosOrigen->removeElement($procesosOrigen);
    }

    /**
     * Get procesosOrigen
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcesosOrigen()
    {
        return $this->procesosOrigen;
    }

    /**
     * Add procesosDestino
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesosDestino
     *
     * @return ProcesoFaena
     */
    public function addProcesosDestino(\GestionFaenaBundle\Entity\ProcesoFaena $procesosDestino)
    {
        $this->procesosDestino[] = $procesosDestino;

        return $this;
    }

    /**
     * Remove procesosDestino
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesosDestino
     */
    public function removeProcesosDestino(\GestionFaenaBundle\Entity\ProcesoFaena $procesosDestino)
    {
        $this->procesosDestino->removeElement($procesosDestino);
    }

    /**
     * Get procesosDestino
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcesosDestino()
    {
        return $this->procesosDestino;
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
        $this->articulos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->procesosOrigen = new \Doctrine\Common\Collections\ArrayCollection();
        $this->procesosDestino = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return ProcesoFaena
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }
}
