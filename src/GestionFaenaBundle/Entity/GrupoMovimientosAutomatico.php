<?php

namespace GestionFaenaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoMovimientosAutomatico
 *
 * @ORM\Table(name="sp_gst_gpo_mov_auto")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\GrupoMovimientosAutomaticoRepository")
 */
class GrupoMovimientosAutomatico
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
     * @var float
     *
     * @ORM\Column(name="orden", type="float")
     */
    private $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="string", length=255)
     */
    private $detalle;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="automaticos") 
    * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
    */      
    private $procesoFaena;

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\faena\MovimientoAutomatico", mappedBy="grupo", cascade={"persist", "remove"})
     * @ORM\OrderBy({"ordenEjecucion" = "ASC"})
     */
    private $automaticos;

    /**
     * @ORM\Column(name="manual", type="boolean", options={"default":false})
     */
    private $manual = false; //indica si el movimiento debe realizarce de manera manual o totalmente automatico

    /**
     * @ORM\Column(name="eliminado", type="boolean", options={"default":false})
     */
    private $eliminado = false; 


    public function getMovimientoManual()
    {
        if (count($this->automaticos))
        {
            return $this->automaticos->first();
        }
        return null;
    }

    public function __toString()
    {
        return $this->nombre;
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
     * Set orden
     *
     * @param float $orden
     *
     * @return GrupoMovimientosAutomatico
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return float
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GrupoMovimientosAutomatico
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
     * Constructor
     */
    public function __construct()
    {
        $this->automaticos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set procesoFaena
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena
     *
     * @return GrupoMovimientosAutomatico
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
     * Add automatico
     *
     * @param \GestionFaenaBundle\Entity\faena\MovimientoAutomatico $automatico
     *
     * @return GrupoMovimientosAutomatico
     */
    public function addAutomatico(\GestionFaenaBundle\Entity\faena\MovimientoAutomatico $automatico)
    {
        $this->automaticos[] = $automatico;

        return $this;
    }

    /**
     * Remove automatico
     *
     * @param \GestionFaenaBundle\Entity\faena\MovimientoAutomatico $automatico
     */
    public function removeAutomatico(\GestionFaenaBundle\Entity\faena\MovimientoAutomatico $automatico)
    {
        $this->automaticos->removeElement($automatico);
    }

    /**
     * Get automaticos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAutomaticos()
    {
        return $this->automaticos;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return GrupoMovimientosAutomatico
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set manual
     *
     * @param boolean $manual
     *
     * @return GrupoMovimientosAutomatico
     */
    public function setManual($manual)
    {
        $this->manual = $manual;

        return $this;
    }

    /**
     * Get manual
     *
     * @return boolean
     */
    public function getManual()
    {
        return $this->manual;
    }

    /**
     * Set pasoProceso
     *
     * @param \GestionFaenaBundle\Entity\PasoProceso $pasoProceso
     *
     * @return GrupoMovimientosAutomatico
     */
    public function setPasoProceso(\GestionFaenaBundle\Entity\PasoProceso $pasoProceso = null)
    {
        $this->pasoProceso = $pasoProceso;

        return $this;
    }

    /**
     * Get pasoProceso
     *
     * @return \GestionFaenaBundle\Entity\PasoProceso
     */
    public function getPasoProceso()
    {
        return $this->pasoProceso;
    }

    /**
     * Set eliminado
     *
     * @param boolean $eliminado
     *
     * @return GrupoMovimientosAutomatico
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * Get eliminado
     *
     * @return boolean
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }
}
