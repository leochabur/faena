<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovimientoAutomatico
 *
 * @ORM\Table(name="sp_gpo_mov_auto")
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
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\GrupoMovimientosAutomatico", inversedBy="automaticos") 
    * @ORM\JoinColumn(name="id_gpo_mov", referencedColumnName="id")
    */      
    private $grupo;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena") 
    * @ORM\JoinColumn(name="id_proc_fan_destino", referencedColumnName="id", nullable=true)
    */      
    private $procesoDestinoDefault;

    /**
    * @ORM\OneToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto", inversedBy="desencadenado") 
    * @ORM\JoinColumn(name="id_proc_des", referencedColumnName="id", nullable=true)
    */   
    private $desencadenante; //para los moviemientos que se deben generar despues de otros de manera automatica

    /**
     * @ORM\Column(name="eliminado", type="boolean", options={"default":false})
     */
    private $eliminado = false; 
    

    public function getVistaEdicion()
    {
        return $this->articuloAtributoConcepto->getVistaEdicion();
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

    /**
     * Set grupo
     *
     * @param \GestionFaenaBundle\Entity\GrupoMovimientosAutomatico $grupo
     *
     * @return MovimientoAutomatico
     */
    public function setGrupo(\GestionFaenaBundle\Entity\GrupoMovimientosAutomatico $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \GestionFaenaBundle\Entity\GrupoMovimientosAutomatico
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set desencadenante
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $desencadenante
     *
     * @return MovimientoAutomatico
     */
    public function setDesencadenante(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $desencadenante = null)
    {
        $this->desencadenante = $desencadenante;

        return $this;
    }

    /**
     * Get desencadenante
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto
     */
    public function getDesencadenante()
    {
        return $this->desencadenante;
    }

    /**
     * Set eliminado
     *
     * @param boolean $eliminado
     *
     * @return MovimientoAutomatico
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
