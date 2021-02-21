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
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\GrupoMovimientosAutomatico", mappedBy="procesoFaena", cascade={"persist", "remove"})
     * @ORM\OrderBy({"orden" = "ASC"})
     */
    private $automaticos;

    /**
     * @ORM\Column(name="romanea", type="boolean", options={"default":false})
     */
    private $romanea = false; 

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\Articulo") 
    * @ORM\JoinColumn(name="id_art_base_tran", referencedColumnName="id", nullable=true)
    */      
    private $articuloBase; //utilizado en particular por el proceso que romanea para poder generar los ArticuloAtributoConepto y asignarle asi el articulo origen de transformacion de manera automatica

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto") 
    * @ORM\JoinColumn(name="id_atr_abst_base", referencedColumnName="id", nullable=true)
    */      
    private $atributoAbstractoBase; //utilizado en particular por el proceso que romanea para generar el atributo manual de manera automatica

    /**
     * @ORM\Column(name="generaTransito", type="boolean", options={"default":false})
     */
    private $generaTransito = false; //indica si el proceso genera excesos que deben almacenarse para ser procesado otro dia

    /**
     * @ORM\Column(name="promedioDefault", type="boolean", options={"default":false})
     */
    private $promedioDefault = false; //indica si el proceso mantiene actualizado el peso promedio de aves (Es utilizado en el caso del proceso de transito para mantener el ultimo peso promedio generado)

    /**
     * @ORM\OneToMany(targetEntity="PasoProceso", mappedBy="procesoFaena")
     * @ORM\OrderBy({"orden" = "ASC"})
     */
    private $pasos;

    /**
     * @ORM\Column(name="generaVentas", type="boolean", options={"default":false})
     */
    private $generaVentas = false; //para el proceso de camaras indica si el proceso genera ventas (Sucursales, Clientes, Exportaciones etc)

    /**
     * @ORM\OneToMany(targetEntity="ConceptoVenta", mappedBy="procesoFaena")
     */
    private $ventas;

    /**
     * @ORM\Column(name="admiteCombinado", type="boolean", options={"default":false})
     */
    private $admiteCombinado = false; //para el caso del proceso de congeamiento que debe hacer un movimiento combinado ya que saca articulos abstractos e ingresa en la camara productos concretos

    /**
     * @ORM\OneToMany(targetEntity="GestionFaenaBundle\Entity\faena\AtributoPorArticuloPorProceso", mappedBy="proceso")
     */
    private $atributosArticulo;


    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto") 
    * @ORM\JoinColumn(name="id_atr_abst_venta", referencedColumnName="id", nullable=true)
    */      
    private $atributoVenta;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\UnidadMedida") 
    * @ORM\JoinColumn(name="id_unit_mdda_venta", referencedColumnName="id", nullable=true)
    */      
    private $unidadMedidaventa;

    /**
    * @ORM\OneToMany(targetEntity="GestionVentasBundle\Entity\options\EntidadExternaConcepto", mappedBy="proceso")
    */      
    private $entidadesConcepto;


    public function atributoAsignadoAArticulo(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo,
                                              \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo)
    {
        $define = 'N';
        foreach ($this->atributosArticulo as $aa)
        {
            if ($aa->getArticulo() == $articulo)
            {
                return $aa->getAtributos()->contains($atributo);
            }
        }
        return $define;

    }


    public function getConceptosOfVentas()
    {
        $conceptos = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->ventas as $vta)
        {
            $conceptos[] = $vta->getConcepto();
        }
        return $conceptos;
    }

    //dado un Articulo devuelve si el mismo se encuentra definido para manejar el stock - Devuelve un objeto FactorCalculo
    public function existeArticuloDefinidoManejoStock(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo)
    {
        foreach ($this->manejosStock as $stock) {
            if ($stock->getArticulo() == $articulo)
                return $stock;
        }
        return null;
    }


    //dado un Articulo devuelve si el mismo se encuentra definido para manejar el stock - Devuelve un objeto FactorCalculo
    public function existeArticuloConIdDefinidoManejoStock($idArticulo)
    {
        foreach ($this->manejosStock as $stock) {
            if ($stock->getArticulo()->getId() == $idArticulo)
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
        $this->pasos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ventas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->atributosArticulo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->entidadesConcepto = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set articuloBase
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Articulo $articuloBase
     *
     * @return ProcesoFaena
     */
    public function setArticuloBase(\GestionFaenaBundle\Entity\gestionBD\Articulo $articuloBase = null)
    {
        $this->articuloBase = $articuloBase;

        return $this;
    }

    /**
     * Get articuloBase
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Articulo
     */
    public function getArticuloBase()
    {
        return $this->articuloBase;
    }

    /**
     * Set atributoAbstractoBase
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributoAbstractoBase
     *
     * @return ProcesoFaena
     */
    public function setAtributoAbstractoBase(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributoAbstractoBase = null)
    {
        $this->atributoAbstractoBase = $atributoAbstractoBase;

        return $this;
    }

    /**
     * Get atributoAbstractoBase
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto
     */
    public function getAtributoAbstractoBase()
    {
        return $this->atributoAbstractoBase;
    }

    /**
     * Add automatico
     *
     * @param \GestionFaenaBundle\Entity\faena\MovimientoAutomatico $automatico
     *
     * @return ProcesoFaena
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
     * Set generaTransito
     *
     * @param boolean $generaTransito
     *
     * @return ProcesoFaena
     */
    public function setGeneraTransito($generaTransito)
    {
        $this->generaTransito = $generaTransito;

        return $this;
    }

    /**
     * Get generaTransito
     *
     * @return boolean
     */
    public function getGeneraTransito()
    {
        return $this->generaTransito;
    }

    /**
     * Set promedioDefault
     *
     * @param boolean $promedioDefault
     *
     * @return ProcesoFaena
     */
    public function setPromedioDefault($promedioDefault)
    {
        $this->promedioDefault = $promedioDefault;

        return $this;
    }

    /**
     * Get promedioDefault
     *
     * @return boolean
     */
    public function getPromedioDefault()
    {
        return $this->promedioDefault;
    }

    /**
     * Add paso
     *
     * @param \GestionFaenaBundle\Entity\PasoProceso $paso
     *
     * @return ProcesoFaena
     */
    public function addPaso(\GestionFaenaBundle\Entity\PasoProceso $paso)
    {
        $this->pasos[] = $paso;

        return $this;
    }

    /**
     * Remove paso
     *
     * @param \GestionFaenaBundle\Entity\PasoProceso $paso
     */
    public function removePaso(\GestionFaenaBundle\Entity\PasoProceso $paso)
    {
        $this->pasos->removeElement($paso);
    }

    /**
     * Get pasos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPasos()
    {
        return $this->pasos;
    }

    /**
     * Set generaVentas
     *
     * @param boolean $generaVentas
     *
     * @return ProcesoFaena
     */
    public function setGeneraVentas($generaVentas)
    {
        $this->generaVentas = $generaVentas;

        return $this;
    }

    /**
     * Get generaVentas
     *
     * @return boolean
     */
    public function getGeneraVentas()
    {
        return $this->generaVentas;
    }

    /**
     * Add venta
     *
     * @param \GestionFaenaBundle\Entity\ConceptoVenta $venta
     *
     * @return ProcesoFaena
     */
    public function addVenta(\GestionFaenaBundle\Entity\ConceptoVenta $venta)
    {
        $this->ventas[] = $venta;

        return $this;
    }

    /**
     * Remove venta
     *
     * @param \GestionFaenaBundle\Entity\ConceptoVenta $venta
     */
    public function removeVenta(\GestionFaenaBundle\Entity\ConceptoVenta $venta)
    {
        $this->ventas->removeElement($venta);
    }

    /**
     * Get ventas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVentas()
    {
        return $this->ventas;
    }

    /**
     * Set admiteCombinado
     *
     * @param boolean $admiteCombinado
     *
     * @return ProcesoFaena
     */
    public function setAdmiteCombinado($admiteCombinado)
    {
        $this->admiteCombinado = $admiteCombinado;

        return $this;
    }

    /**
     * Get admiteCombinado
     *
     * @return boolean
     */
    public function getAdmiteCombinado()
    {
        return $this->admiteCombinado;
    }

    /**
     * Add atributosArticulo
     *
     * @param \GestionFaenaBundle\Entity\faena\AtributoPorArticuloPorProceso $atributosArticulo
     *
     * @return ProcesoFaena
     */
    public function addAtributosArticulo(\GestionFaenaBundle\Entity\faena\AtributoPorArticuloPorProceso $atributosArticulo)
    {
        $this->atributosArticulo[] = $atributosArticulo;

        return $this;
    }

    /**
     * Remove atributosArticulo
     *
     * @param \GestionFaenaBundle\Entity\faena\AtributoPorArticuloPorProceso $atributosArticulo
     */
    public function removeAtributosArticulo(\GestionFaenaBundle\Entity\faena\AtributoPorArticuloPorProceso $atributosArticulo)
    {
        $this->atributosArticulo->removeElement($atributosArticulo);
    }

    /**
     * Get atributosArticulo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAtributosArticulo()
    {
        return $this->atributosArticulo;
    }

    /**
     * Set atributoVenta
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributoVenta
     *
     * @return ProcesoFaena
     */
    public function setAtributoVenta(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributoVenta = null)
    {
        $this->atributoVenta = $atributoVenta;

        return $this;
    }

    /**
     * Get atributoVenta
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto
     */
    public function getAtributoVenta()
    {
        return $this->atributoVenta;
    }

    /**
     * Add entidadesConcepto
     *
     * @param \GestionVentasBundle\Entity\options\EntidadExternaConcepto $entidadesConcepto
     *
     * @return ProcesoFaena
     */
    public function addEntidadesConcepto(\GestionVentasBundle\Entity\options\EntidadExternaConcepto $entidadesConcepto)
    {
        $this->entidadesConcepto[] = $entidadesConcepto;

        return $this;
    }

    /**
     * Remove entidadesConcepto
     *
     * @param \GestionVentasBundle\Entity\options\EntidadExternaConceptoRepository $entidadesConcepto
     */
    public function removeEntidadesConcepto(\GestionVentasBundle\Entity\options\EntidadExternaConcepto $entidadesConcepto)
    {
        $this->entidadesConcepto->removeElement($entidadesConcepto);
    }

    /**
     * Get entidadesConcepto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntidadesConcepto()
    {
        return $this->entidadesConcepto;
    }

    /**
     * Set unidadMedidaventa
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\UnidadMedida $unidadMedidaventa
     *
     * @return ProcesoFaena
     */
    public function setUnidadMedidaventa(\GestionFaenaBundle\Entity\gestionBD\UnidadMedida $unidadMedidaventa = null)
    {
        $this->unidadMedidaventa = $unidadMedidaventa;

        return $this;
    }

    /**
     * Get unidadMedidaventa
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\UnidadMedida
     */
    public function getUnidadMedidaventa()
    {
        return $this->unidadMedidaventa;
    }
}
