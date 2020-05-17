<?php

namespace GestionSigcerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Solicitud
 *
 * @ORM\Table(name="sig_sol_as_gpo")
 * @ORM\Entity(repositoryClass="GestionSigcerBundle\Repository\SolicitudRepository")
 */
class Solicitud
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
    * @ORM\ManyToOne(targetEntity="GestionSigcerBundle\Entity\opciones\Zona") 
    * @ORM\JoinColumn(name="id_zona", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $zona;

    /**
     * @var string
     *
     * @ORM\Column(name="lugarDestino", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $lugarDestino;

    /**
     *
     * @ORM\Column(name="precintos", type="integer")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $precintos;

    /**
     * @var string
     *
     * @ORM\Column(name="precintoAduana", type="string", length=255, nullable=true)
     */
    private $precintoAduana;

    /**
     * @var string
     *
     * @ORM\Column(name="precintoSenasa", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $precintoSenasa;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="remitoNumero", type="string", length=255, nullable=true)
     */
    private $remitoNumero;

    /**
     * @var float
     *
     * @ORM\Column(name="temperatura", type="float")
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $temperatura = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="termoTemperatura", type="float", nullable=true)
     */
    private $termoTemperatura;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="termoTiempo", type="time", nullable=true)
     */
    private $termoTiempo;

    /**
    * @ORM\ManyToOne(targetEntity="GestionSigcerBundle\Entity\opciones\Cliente") 
    * @ORM\JoinColumn(name="id_cliente", referencedColumnName="id")
    */      
    private $cliente;

    /**
    * @ORM\ManyToOne(targetEntity="GestionSigcerBundle\Entity\opciones\Camion") 
    * @ORM\JoinColumn(name="id_camion", referencedColumnName="id")
    * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
    */      
    private $camion;

    /**
     * @ORM\OneToMany(targetEntity="DetalleSolicitud", mappedBy="solicitud", cascade={"ALL"})
     */
    private $detalles;

    /**
    * @ORM\ManyToOne(targetEntity="GrupoSolicitud", inversedBy="solicitudes") 
    * @ORM\JoinColumn(name="id_gr_sol", referencedColumnName="id")
    */      
    private $grupo;
    
    public function __toString()
    {
        try{
        return "Zona: ".$this->zona ." - Cliente: ".$this->cliente. " - Camion: ".$this->camion;
        }
        catch (\Exception $e){ return $this->zona;}
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
     * Set lugarDestino
     *
     * @param string $lugarDestino
     *
     * @return Solicitud
     */
    public function setLugarDestino($lugarDestino)
    {
        $this->lugarDestino = $lugarDestino;

        return $this;
    }

    /**
     * Get lugarDestino
     *
     * @return string
     */
    public function getLugarDestino()
    {
        return $this->lugarDestino;
    }

    /**
     * Set precintoAduana
     *
     * @param string $precintoAduana
     *
     * @return Solicitud
     */
    public function setPrecintoAduana($precintoAduana)
    {
        $this->precintoAduana = $precintoAduana;

        return $this;
    }

    /**
     * Get precintoAduana
     *
     * @return string
     */
    public function getPrecintoAduana()
    {
        return $this->precintoAduana;
    }

    /**
     * Set precintoSenasa
     *
     * @param string $precintoSenasa
     *
     * @return Solicitud
     */
    public function setPrecintoSenasa($precintoSenasa)
    {
        $this->precintoSenasa = $precintoSenasa;

        return $this;
    }

    /**
     * Get precintoSenasa
     *
     * @return string
     */
    public function getPrecintoSenasa()
    {
        return $this->precintoSenasa;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Solicitud
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set remitoNumero
     *
     * @param string $remitoNumero
     *
     * @return Solicitud
     */
    public function setRemitoNumero($remitoNumero)
    {
        $this->remitoNumero = $remitoNumero;

        return $this;
    }

    /**
     * Get remitoNumero
     *
     * @return string
     */
    public function getRemitoNumero()
    {
        return $this->remitoNumero;
    }

    /**
     * Set temperatura
     *
     * @param float $temperatura
     *
     * @return Solicitud
     */
    public function setTemperatura($temperatura)
    {
        $this->temperatura = $temperatura;

        return $this;
    }

    /**
     * Get temperatura
     *
     * @return float
     */
    public function getTemperatura()
    {
        return $this->temperatura;
    }

    /**
     * Set termoTemperatura
     *
     * @param float $termoTemperatura
     *
     * @return Solicitud
     */
    public function setTermoTemperatura($termoTemperatura)
    {
        $this->termoTemperatura = $termoTemperatura;

        return $this;
    }

    /**
     * Get termoTemperatura
     *
     * @return float
     */
    public function getTermoTemperatura()
    {
        return $this->termoTemperatura;
    }

    /**
     * Set termoTiempo
     *
     * @param \DateTime $termoTiempo
     *
     * @return Solicitud
     */
    public function setTermoTiempo($termoTiempo)
    {
        $this->termoTiempo = $termoTiempo;

        return $this;
    }

    /**
     * Get termoTiempo
     *
     * @return \DateTime
     */
    public function getTermoTiempo()
    {
        return $this->termoTiempo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detalles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set cliente
     *
     * @param \GestionSigcerBundle\Entity\opciones\Cliente $cliente
     *
     * @return Solicitud
     */
    public function setCliente(\GestionSigcerBundle\Entity\opciones\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \GestionSigcerBundle\Entity\opciones\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set camion
     *
     * @param \GestionSigcerBundle\Entity\opciones\Camion $camion
     *
     * @return Solicitud
     */
    public function setCamion(\GestionSigcerBundle\Entity\opciones\Camion $camion = null)
    {
        $this->camion = $camion;

        return $this;
    }

    /**
     * Get camion
     *
     * @return \GestionSigcerBundle\Entity\opciones\Camion
     */
    public function getCamion()
    {
        return $this->camion;
    }

    /**
     * Add detalle
     *
     * @param \GestionSigcerBundle\Entity\DetalleSolicitud $detalle
     *
     * @return Solicitud
     */
    public function addDetalle(\GestionSigcerBundle\Entity\DetalleSolicitud $detalle)
    {
        $this->detalles[] = $detalle;

        return $this;
    }

    /**
     * Remove detalle
     *
     * @param \GestionSigcerBundle\Entity\DetalleSolicitud $detalle
     */
    public function removeDetalle(\GestionSigcerBundle\Entity\DetalleSolicitud $detalle)
    {
        $this->detalles->removeElement($detalle);
    }

    /**
     * Get detalles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetalles()
    {
        return $this->detalles;
    }

    /**
     * Set grupo
     *
     * @param \GestionSigcerBundle\Entity\GrupoSolicitud $grupo
     *
     * @return Solicitud
     */
    public function setGrupo(\GestionSigcerBundle\Entity\GrupoSolicitud $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \GestionSigcerBundle\Entity\GrupoSolicitud
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set zona
     *
     * @param \GestionSigcerBundle\Entity\opciones\Zona $zona
     *
     * @return Solicitud
     */
    public function setZona(\GestionSigcerBundle\Entity\opciones\Zona $zona = null)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return \GestionSigcerBundle\Entity\opciones\Zona
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set precintos
     *
     * @param integer $precintos
     *
     * @return Solicitud
     */
    public function setPrecintos($precintos)
    {
        $this->precintos = $precintos;

        return $this;
    }

    /**
     * Get precintos
     *
     * @return integer
     */
    public function getPrecintos()
    {
        return $this->precintos;
    }
}
