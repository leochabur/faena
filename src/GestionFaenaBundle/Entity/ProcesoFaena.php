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
     * @ORM\ManyToOne(targetEntity="ProcesoFaena")
     * @ORM\JoinColumn(name="id_proc_dest_def", referencedColumnName="id")
    */
    
    private $procesosDestinoDefault; //para las trasferencias, cuando se configura automaticas, indica a cual es el proceso que se le debe transferir el stock de los articulos

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso", mappedBy="procesoFaena")
     */
    private $conceptos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", options={"default":true})
     */
    private $activo = true; 


    /**
     *
     * @ORM\Column(name="permanente", type="boolean", options={"default":false})
     */
    private $permanente = false; //indica si se debe instanciar cada vez que se crea una faena nueva, caso contrario, se instancia una sola vez (por ej la Camara que acopia productos de todas las faenas o el proceso de Deposito En Transito que se almacena para ser procesado al otro dia) 

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="procesos")
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria", mappedBy="procesoFaena")
     */
    private $procesosFaenaDiaria;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\FactorCalculo")
     * @ORM\JoinTable(name="sp_man_stock_proc_fan",
     *      joinColumns={@ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_fact_op", referencedColumnName="id")}
     *      )
     */
    private $manejosStock;

    /**
     * @ORM\Column(name="orden", type="integer", options={"default":0})
     */
    private $orden = 0; 

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\AjusteMovimiento", mappedBy="proceso")
     */
    private $ajustes;

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto", mappedBy="procesoFaena")
     */
    private $automaticos;

    /**
     * @ORM\Column(name="romanea", type="boolean", options={"default":false})
     */
    private $romanea = false; 

    //dado un Articulo devuelve si el mismo se encuentra definido para manejar el stock - Devuelve un objeto FactorCalculo
    public function existeArticuloDefinidoManejoStock(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo)
    {
        foreach ($this->manejosStock as $stock) {
            if ($stock->getArticulo() == $articulo)
                return $stock;
        }
        return null;
    }

    public abstract function getType();
    public abstract function getInstance();

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
        $this->conceptos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->manejosStock = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ajustes = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getArticulosActivos()
    {
        $articulos = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->articulos as $art) {
            if ($art->getActivo())
                $articulos[] = $art;
        }
        return $articulos;
    }

    /**
     * Add concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     *
     * @return ProcesoFaena
     */
    public function addConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto)
    {
        $this->conceptos[] = $concepto;

        return $this;
    }

    /**
     * Remove concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     */
    public function removeConcepto(\GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto)
    {
        $this->conceptos->removeElement($concepto);
    }

    /**
     * Get conceptos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConceptos()
    {
        return $this->conceptos;
    }

    /**
     * Add usuario
     *
     * @param \AppBundle\Entity\User $usuario
     *
     * @return ProcesoFaena
     */
    public function addUsuario(\AppBundle\Entity\User $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \AppBundle\Entity\User $usuario
     */
    public function removeUsuario(\AppBundle\Entity\User $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Add procesosFaenaDiarium
     *
     * @param \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesosFaenaDiarium
     *
     * @return ProcesoFaena
     */
    public function addProcesosFaenaDiarium(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesosFaenaDiarium)
    {
        $this->procesosFaenaDiaria[] = $procesosFaenaDiarium;

        return $this;
    }

    /**
     * Remove procesosFaenaDiarium
     *
     * @param \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesosFaenaDiarium
     */
    public function removeProcesosFaenaDiarium(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesosFaenaDiarium)
    {
        $this->procesosFaenaDiaria->removeElement($procesosFaenaDiarium);
    }

    /**
     * Get procesosFaenaDiaria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcesosFaenaDiaria()
    {
        return $this->procesosFaenaDiaria;
    }

    

    /**
     * Add manejosStock
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\FactorCalculo $manejosStock
     *
     * @return ProcesoFaena
     */
    public function addManejosStock(\GestionFaenaBundle\Entity\gestionBD\FactorCalculo $manejosStock)
    {
        $this->manejosStock[] = $manejosStock;

        return $this;
    }

    /**
     * Remove manejosStock
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\FactorCalculo $manejosStock
     */
    public function removeManejosStock(\GestionFaenaBundle\Entity\gestionBD\FactorCalculo $manejosStock)
    {
        $this->manejosStock->removeElement($manejosStock);
    }

    /**
     * Get manejosStock
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManejosStock()
    {
        return $this->manejosStock;
    }

    public function getArticulosStock()
    {
        $articulos = array();
        foreach ($this->manejosStock as $fatcor) {
            $articulos[] = $fatcor->getArticulo();
        }
        return $articulos;
    }

    /**
     * Set permanente
     *
     * @param boolean $permanente
     *
     * @return ProcesoFaena
     */
    public function setPermanente($permanente)
    {
        $this->permanente = $permanente;

        return $this;
    }

    /**
     * Get permanente
     *
     * @return boolean
     */
    public function getPermanente()
    {
        return $this->permanente;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return ProcesoFaena
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Add ajuste
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $ajuste
     *
     * @return ProcesoFaena
     */
    public function addAjuste(\GestionFaenaBundle\Entity\ProcesoFaena $ajuste)
    {
        $this->ajustes[] = $ajuste;

        return $this;
    }

    /**
     * Remove ajuste
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $ajuste
     */
    public function removeAjuste(\GestionFaenaBundle\Entity\ProcesoFaena $ajuste)
    {
        $this->ajustes->removeElement($ajuste);
    }

    /**
     * Get ajustes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAjustes()
    {
        return $this->ajustes;
    }

    public function getAjusteAAplicar(\GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto $tipoMovimiento,
                                         \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $conceptoMovimiento,
                                         \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo)
    {
        foreach ($this->ajustes as $ajuste) 
        {
            if (($ajuste->getConceptoMovimiento() == $conceptoMovimiento) &&
                ($ajuste->getArticulo() == $articulo) &&
                ($ajuste->getProceso() == $this) &&
                ($ajuste->getTipoMovimiento() == $tipoMovimiento))
            {
                return $ajuste->getFactorAjuste();
            }
        }
        return 1;
    }

    /**
     * Add automatico
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $automatico
     *
     * @return ProcesoFaena
     */
    public function addAutomatico(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $automatico)
    {
        $this->automaticos[] = $automatico;

        return $this;
    }

    /**
     * Remove automatico
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $automatico
     */
    public function removeAutomatico(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $automatico)
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
     * Set procesosDestinoDefault
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesosDestinoDefault
     *
     * @return ProcesoFaena
     */
    public function setProcesosDestinoDefault(\GestionFaenaBundle\Entity\ProcesoFaena $procesosDestinoDefault = null)
    {
        $this->procesosDestinoDefault = $procesosDestinoDefault;

        return $this;
    }

    /**
     * Get procesosDestinoDefault
     *
     * @return \GestionFaenaBundle\Entity\ProcesoFaena
     */
    public function getProcesosDestinoDefault()
    {
        return $this->procesosDestinoDefault;
    }

    /**
     * Set romanea
     *
     * @param boolean $romanea
     *
     * @return ProcesoFaena
     */
    public function setRomanea($romanea)
    {
        $this->romanea = $romanea;

        return $this;
    }

    /**
     * Get romanea
     *
     * @return boolean
     */
    public function getRomanea()
    {
        return $this->romanea;
    }
}
