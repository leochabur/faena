<?php

namespace GestionFaenaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PasoProceso
 *
 * @ORM\Table(name="sp_gst_pso_proc")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\PasoProcesoRepository")
 */
class PasoProceso
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
     * @var int
     *
     * @ORM\Column(name="orden", type="float")
     */
    private $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="text")
     */
    private $detalle;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="pasos") 
    * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
    */      
    private $procesoFaena;

    /**
    * @ORM\OneToOne(targetEntity="GestionFaenaBundle\Entity\GrupoMovimientosAutomatico", inversedBy="pasoProceso") 
    * @ORM\JoinColumn(name="id_grupo_mov_auto", referencedColumnName="id", nullable=true)
    */   
    private $grupoMovimiento;
    
    /**
     * @ORM\OneToOne(targetEntity="PasoProcesoRealizado", mappedBy="paso")
     */
    private $pasoRealizado;

    /**
     * @ORM\Column(name="eliminado", type="boolean", options={"default":false})
     */
    private $eliminado = false; 
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
     * @param integer $orden
     *
     * @return PasoProceso
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return int
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return PasoProceso
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
     * Set procesoFaena
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena
     *
     * @return PasoProceso
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
     * Set grupoMovimiento
     *
     * @param \GestionFaenaBundle\Entity\GrupoMovimientosAutomatico $grupoMovimiento
     *
     * @return PasoProceso
     */
    public function setGrupoMovimiento(\GestionFaenaBundle\Entity\GrupoMovimientosAutomatico $grupoMovimiento = null)
    {
        $this->grupoMovimiento = $grupoMovimiento;

        return $this;
    }

    /**
     * Get grupoMovimiento
     *
     * @return \GestionFaenaBundle\Entity\GrupoMovimientosAutomatico
     */
    public function getGrupoMovimiento()
    {
        return $this->grupoMovimiento;
    }

    /**
     * Set pasoRealizado
     *
     * @param \GestionFaenaBundle\Entity\PasoProcesoRealizado $pasoRealizado
     *
     * @return PasoProceso
     */
    public function setPasoRealizado(\GestionFaenaBundle\Entity\PasoProcesoRealizado $pasoRealizado = null)
    {
        $this->pasoRealizado = $pasoRealizado;

        return $this;
    }

    /**
     * Get pasoRealizado
     *
     * @return \GestionFaenaBundle\Entity\PasoProcesoRealizado
     */
    public function getPasoRealizado()
    {
        return $this->pasoRealizado;
    }

    /**
     * Set eliminado
     *
     * @param boolean $eliminado
     *
     * @return PasoProceso
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
