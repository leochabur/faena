<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovimientoStock
 *
 * @ORM\Table(name="sp_st_mov_abst")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\MovimientoStockRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({1:"MovimientoStock",2: "EntradaStock", 3: "SalidaStock", 4: "TransformarStock"})
 */
abstract class MovimientoStock
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
    * @ORM\ManyToOne(targetEntity="ProcesoFaenaDiaria", inversedBy="movimientos") 
    * @ORM\JoinColumn(name="id_proc_fan_day", referencedColumnName="id")
    */      
    private $procesoFnDay;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena") 
    * @ORM\JoinColumn(name="id_art_proc_fan", referencedColumnName="id")
    */      
    private $artProcFaena;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\faena\ConceptoMovimiento") 
    * @ORM\JoinColumn(name="id_concepto", referencedColumnName="id")
    */      
    private $concepto;

    /**
     * @ORM\OneToMany(targetEntity="ValorAtributo", mappedBy="movimiento", cascade={"persist", "remove"})
     */
    private $valores;


    public function generateAtributes()
    {
        foreach ($this->artProcFaena->getAtributos() as $atributo) {
            if ($this->concepto->manejaAtributo($atributo))
                $this->addValore($atributo->getAtributo()->getEntityValorAtributo($atributo));
        }        
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valores = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set procesoFnDay
     *
     * @param \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesoFnDay
     *
     * @return MovimientoStock
     */
    public function setProcesoFnDay(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $procesoFnDay = null)
    {
        $this->procesoFnDay = $procesoFnDay;

        return $this;
    }

    /**
     * Get procesoFnDay
     *
     * @return \GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria
     */
    public function getProcesoFnDay()
    {
        return $this->procesoFnDay;
    }

    /**
     * Set artProcFaena
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena $artProcFaena
     *
     * @return MovimientoStock
     */
    public function setArtProcFaena(\GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena $artProcFaena = null)
    {
        $this->artProcFaena = $artProcFaena;

        return $this;
    }

    /**
     * Get artProcFaena
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena
     */
    public function getArtProcFaena()
    {
        return $this->artProcFaena;
    }

    /**
     * Add valore
     *
     * @param \GestionFaenaBundle\Entity\faena\ValorAtributo $valore
     *
     * @return MovimientoStock
     */
    public function addValore(\GestionFaenaBundle\Entity\faena\ValorAtributo $valore)
    {
        $this->valores[] = $valore;
        $valore->setMovimiento($this);
        return $this;
    }

    /**
     * Remove valore
     *
     * @param \GestionFaenaBundle\Entity\faena\ValorAtributo $valore
     */
    public function removeValore(\GestionFaenaBundle\Entity\faena\ValorAtributo $valore)
    {
        $this->valores->removeElement($valore);
    }

    /**
     * Get valores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * Set concepto
     *
     * @param \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto
     *
     * @return MovimientoStock
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

    public function getValorConAtributo($atributo)
    {
        foreach ($this->valores as $value) {
            if ($value->getAtributo()->getAtributo() == $atributo){
                return $value;
            }
        }
        return null;
    }

    public function getValorAtributoConNombre($nombre)
    {
        foreach ($this->valores as $value) {
            if ($value->getAtributo()->getAtributo()->getNombre() == $nombre){
                return $value->getData();
            }
        }
        return null;
    }

    public abstract function updateValues($promedio);

    public abstract function getType();

    public function updateValueAtribute($valor)
    {
        return $valor;

    }

    public function getConceptos($conceptos){
        
    }
}
