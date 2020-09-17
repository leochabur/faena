<?php

namespace GestionFaenaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConceptoVenta
 *
 * @ORM\Table(name="sp_gst_conc_vta")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\ConceptoVentaRepository")
 */
class ConceptoVenta
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
     * @ORM\Column(name="entidadExterna", type="string", length=255)
     */
    private $entidadExterna;

    /**
    * @ORM\ManyToOne(targetEntity="ProcesoFaena", inversedBy="ventas") 
    * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
    */      
    private $procesoFaena;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimiento") 
    * @ORM\JoinColumn(name="id_concepto", referencedColumnName="id")
    */      
    private $concepto;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto") 
    * @ORM\JoinColumn(name="id_atr_abst", referencedColumnName="id")
    */      
    private $atributo;


    public function __toString()
    {
        return $this->concepto."";
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
     * Set entidadExterna
     *
     * @param string $entidadExterna
     *
     * @return ConceptoVenta
     */
    public function setEntidadExterna($entidadExterna)
    {
        $this->entidadExterna = $entidadExterna;

        return $this;
    }

    /**
     * Get entidadExterna
     *
     * @return string
     */
    public function getEntidadExterna()
    {
        return $this->entidadExterna;
    }

    /**
     * Set procesoFaena
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena
     *
     * @return ConceptoVenta
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
     * @return ConceptoVenta
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

    /**
     * Set atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo
     *
     * @return ConceptoVenta
     */
    public function setAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo = null)
    {
        $this->atributo = $atributo;

        return $this;
    }

    /**
     * Get atributo
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto
     */
    public function getAtributo()
    {
        return $this->atributo;
    }
}
