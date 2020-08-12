<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovimientoAutomatico
 *
 * @ORM\Table(name="faena_movimiento_automatico")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\MovimientoAutomaticoRepository")
 */
class MovimientoAutomatico
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
     * @ORM\Column(name="ordenEjecucion", type="float")
     */
    private $ordenEjecucion;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto") 
    * @ORM\JoinColumn(name="id_art_atr_con", referencedColumnName="id")
    */   
    private $articuloAtributoConcepto;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="automaticos") 
    * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
    */      
    private $procesoFaena;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena") 
    * @ORM\JoinColumn(name="id_proc_fan_destino", referencedColumnName="id", nullable=true)
    */      
    private $procesoDestinoDefault;


    public function getVistaEdicion()
    {
        return $this->articuloAtributoConcepto->getVistaEdicion()."(".$this->ordenEjecucion.")";
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
     * Set ordenEjecucion
     *
     * @param float $ordenEjecucion
     *
     * @return MovimientoAutomatico
     */
    public function setOrdenEjecucion($ordenEjecucion)
    {
        $this->ordenEjecucion = $ordenEjecucion;

        return $this;
    }

    /**
     * Get ordenEjecucion
     *
     * @return float
     */
    public function getOrdenEjecucion()
    {
        return $this->ordenEjecucion;
    }

    /**
     * Set articuloAtributoConcepto
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $articuloAtributoConcepto
     *
     * @return MovimientoAutomatico
     */
    public function setArticuloAtributoConcepto(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $articuloAtributoConcepto = null)
    {
        $this->articuloAtributoConcepto = $articuloAtributoConcepto;

        return $this;
    }

    /**
     * Get articuloAtributoConcepto
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto
     */
    public function getArticuloAtributoConcepto()
    {
        return $this->articuloAtributoConcepto;
    }

    /**
     * Set procesoFaena
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena
     *
     * @return MovimientoAutomatico
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
     * Set procesoDestinoDefault
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesoDestinoDefault
     *
     * @return MovimientoAutomatico
     */
    public function setProcesoDestinoDefault(\GestionFaenaBundle\Entity\ProcesoFaena $procesoDestinoDefault = null)
    {
        $this->procesoDestinoDefault = $procesoDestinoDefault;

        return $this;
    }

    /**
     * Get procesoDestinoDefault
     *
     * @return \GestionFaenaBundle\Entity\ProcesoFaena
     */
    public function getProcesoDestinoDefault()
    {
        return $this->procesoDestinoDefault;
    }
}
