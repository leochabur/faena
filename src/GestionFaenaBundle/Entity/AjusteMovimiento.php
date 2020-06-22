<?php

namespace GestionFaenaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AjusteMovimiento
 *
 * @ORM\Table(name="sp_gst_adjs_mov_proc")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\AjusteMovimientoRepository")
 */
class AjusteMovimiento
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
     * @ORM\Column(name="factorAjuste", type="float", nullable=true)
     */
    private $factorAjuste;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto")
    * @ORM\JoinColumn(name="id_tipo_movimiento", referencedColumnName="id")
    */
    private $tipoMovimiento;
    
    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimiento") 
    * @ORM\JoinColumn(name="id_concepto_movimiento", referencedColumnName="id")
    */      
    private $conceptoMovimiento;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\Articulo") 
    * @ORM\JoinColumn(name="id_articulo", referencedColumnName="id")
    */      
    private $articulo;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="ajustes") 
    * @ORM\JoinColumn(name="id_proceso_faena", referencedColumnName="id")
    */      
    private $proceso;

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
     * Set factorAjuste
     *
     * @param float $factorAjuste
     *
     * @return AjusteMovimiento
     */
    public function setFactorAjuste($factorAjuste)
    {
        $this->factorAjuste = $factorAjuste;

        return $this;
    }

    /**
     * Get factorAjuste
     *
     * @return float
     */
    public function getFactorAjuste()
    {
        return $this->factorAjuste;
    }

    /**
     * Set tipoMovimiento
     *
     * @param \GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto $tipoMovimiento
     *
     * @return AjusteMovimiento
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
     * Set conceptoMovimiento
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $conceptoMovimiento
     *
     * @return AjusteMovimiento
     */
    public function setConceptoMovimiento(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $conceptoMovimiento = null)
    {
        $this->conceptoMovimiento = $conceptoMovimiento;

        return $this;
    }

    /**
     * Get conceptoMovimiento
     *
     * @return \GestionFaenaBundle\Entity\faena\ConceptoMovimiento
     */
    public function getConceptoMovimiento()
    {
        return $this->conceptoMovimiento;
    }

    /**
     * Set articulo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo
     *
     * @return AjusteMovimiento
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
     * @return AjusteMovimiento
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
}
