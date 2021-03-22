<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * ConceptoMovimientoProceso
 *
 * @ORM\Table(name="sp_st_con_mov_proc")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ConceptoMovimientoProcesoRepository")
 * @UniqueEntity(
 *     fields={"procesoFaena", "concepto", "tipoMovimiento"},
 *     message="Concepto ya asignado al proceso!!"
 * )
 */
class ConceptoMovimientoProceso
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
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="conceptos") 
    * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $procesoFaena;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimiento") 
    * @ORM\JoinColumn(name="id_concepto", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $concepto;

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto", mappedBy="concepto")
     */
    private $articulos;

    /**
    * @ORM\ManyToOne(targetEntity="TipoMovimientoConcepto", inversedBy="conceptosMovimientos")
    * @ORM\JoinColumn(name="id_tipo_movimiento", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $tipoMovimiento;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\Articulo")
    * @ORM\JoinColumn(name="id_art_orig_trans", referencedColumnName="id", nullable=true)
     */
    private $articuloOrigenTransformacion; //para el caso de el movimiento Transformar Stock, define cual es el articulo de origen que se va a transformar

    /**
     *
     * @ORM\Column(name="automatico", type="boolean", options={"default":false})
     */
    private $automatico = false; //cuano genera una transferencia, debe existir la entrada y salida asociadoa al concepto estos son marcadas como automaticos

    /**
     *
     * @ORM\Column(name="activo", type="boolean", options={"default":true})
     */
    private $activo = true; 

    public function getVistaEdicion()
    {
        return $this->procesoFaena."  -  ".$this->concepto."  -  ".$this->tipoMovimiento;
    }

    public function getVistaEdicionV2()
    {
        return $this->procesoFaena."  -  ".$this->concepto."(". $this->id .")  -  ".$this->tipoMovimiento;
    }

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
     * Set procesoFaena
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena
     *
     * @return ConceptoMovimientoProceso
     */
    public function setProcesoFaena(\GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena = null)
    {
        $this->procesoFaena = $procesoFaena;

        return $this;
    }

    /**
     * Get procesoFaena
     *
     * @return \GestionFaenaBundle\Entity\ProcesoFaena
     */
    public function getProcesoFaena()
    {
        return $this->procesoFaena;
    }

    /**
     * Set concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     *
     * @return ConceptoMovimientoProceso
     */
    public function setConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto = null)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return \GestionFaenaBundle\Entity\faena\ConceptoMovimiento
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    public function __toString()
    {
        try
        {
            return $this->concepto."";//." ".$this->procesoFaena;

        }
        catch (\Exception $e) { return "";}
        
    }

    public function getEsa()
    {
        return 1;
    }

    public function getName()
    {
        return $this->concepto."";
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articulos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $articulo
     *
     * @return ConceptoMovimientoProceso
     */
    public function addArticulo(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $articulo)
    {
        $this->articulos[] = $articulo;

        return $this;
    }

    /**
     * Remove articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $articulo
     */
    public function removeArticulo(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $articulo)
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

    public function getListaArticulos()
    {
        $lista = array();
        foreach ($this->articulos as $a) {
            $lista[] = $a."";
        }

        return implode(' - ', $lista);
    }

    public function getTiposMov()
    {
        $lista = array();
        foreach ($this->tiposMovimiento as $a) {
            $lista[] = $a."";
        }

        return implode(' - ', $lista);
    } 

    /**
     * Set tipoMovimiento
     *
     * @param \GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto $tipoMovimiento
     *
     * @return ConceptoMovimientoProceso
     */
    public function setTipoMovimiento(\GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto $tipoMovimiento = null)
    {
        $this->tipoMovimiento = $tipoMovimiento;

        return $this;
    }

    /**
     * Get tipoMovimiento
     *
     * @return \GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto
     */
    public function getTipoMovimiento()
    {
        return $this->tipoMovimiento;
    }

    /**
     * Set articuloOrigenTransformacion
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articuloOrigenTransformacion
     *
     * @return ConceptoMovimientoProceso
     */
    public function setArticuloOrigenTransformacion(\GestionFaenaBundle\Entity\gestionBD\Articulo $articuloOrigenTransformacion = null)
    {
        $this->articuloOrigenTransformacion = $articuloOrigenTransformacion;

        return $this;
    }

    /**
     * Get articuloOrigenTransformacion
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Articulo
     */
    public function getArticuloOrigenTransformacion()
    {
        return $this->articuloOrigenTransformacion;
    }

    /**
     * Set automatico
     *
     * @param boolean $automatico
     *
     * @return ConceptoMovimientoProceso
     */
    public function setAutomatico($automatico)
    {
        $this->automatico = $automatico;

        return $this;
    }

    /**
     * Get automatico
     *
     * @return boolean
     */
    public function getAutomatico()
    {
        return $this->automatico;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return ConceptoMovimientoProceso
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
