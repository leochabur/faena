<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ArticuloProcesoFaena
 *
 * @ORM\Table(name="sp_art_proc_fa")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\ArticuloProcesoFaenaRepository")
  * @UniqueEntity(
 *     fields={"proceso", "articulo"},
 *     message="Ya existe un producto creado para el proceso"
 * )
 */
class ArticuloProcesoFaena
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
    * @ORM\ManyToOne(targetEntity="Articulo") 
    * @ORM\JoinColumn(name="id_art_abs", referencedColumnName="id")
    * @Assert\NotNull(message="En campo no puede permanecer en blanco!")
    */      
    private $articulo;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="articulos") 
    * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
    * @Assert\NotNull(message="En campo no puede permanecer en blanco!")
    */      
    private $proceso;

    /**
     * @ORM\ManyToMany(targetEntity="AtributoProceso", cascade={"persist"})
     * @ORM\JoinTable(name="sp_atr_for_art",
     *      joinColumns={@ORM\JoinColumn(name="id_art_proc_fan", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_atr_art", referencedColumnName="id", unique=true)}
     *      )
     */
    private $atributos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", options={"default":true})
     */
    private $activo = true; 

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
     * Set articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo
     *
     * @return ArticuloProcesoFaena
     */
    public function setArticulo(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo = null)
    {
        $this->articulo = $articulo;

        return $this;
    }

    /**
     * Get articulo
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Articulo
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

    /**
     * Set proceso
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $proceso
     *
     * @return ArticuloProcesoFaena
     */
    public function setProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso = null)
    {
        $this->proceso = $proceso;

        return $this;
    }

    /**
     * Get proceso
     *
     * @return \GestionFaenaBundle\Entity\ProcesoFaena
     */
    public function getProceso()
    {
        return $this->proceso;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->atributos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->articulo->getNombre()."(".$this->proceso.")";
    }

    /**
     * Add atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo
     *
     * @return ArticuloProcesoFaena
     */
    public function addAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo)
    {
        $this->atributos[] = $atributo;

        return $this;
    }

    /**
     * Remove atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo
     */
    public function removeAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo)
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

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return ArticuloProcesoFaena
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
