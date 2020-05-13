<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MovimientoStock
 *
 * @ORM\Table(name="sp_st_mov_abst")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\MovimientoStockRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({1:"MovimientoStock",2: "EntradaStock", 3: "SalidaStock", 5: "TransferirStock"})
 * @ORM\HasLifecycleCallbacks()
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
    * @Assert\NotNull
    */      
    private $procesoFnDay;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\FaenaDiaria") 
    * @ORM\JoinColumn(name="id_fan_day", referencedColumnName="id")
    * @Assert\NotNull
    */      
    private $faenaDiaria; //utilizado porque al poder compartir movimientos de distintas faenas debo poder identificar a cual corresponde

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto") 
    * @ORM\JoinColumn(name="id_art_proc_fan", referencedColumnName="id")
    * @Assert\NotNull(message="Debe seleccionar un articulo!!")
    */      
    private $artProcFaena;

    /**
     * @ORM\OneToMany(targetEntity="ValorAtributo", mappedBy="movimiento", cascade={"persist", "remove"})
     */
    private $valores;

    /**
     * @ORM\OneToOne(targetEntity="TransferirStock", mappedBy="movimientoDestino")
     */
    private $destino;

    /**
     * @ORM\OneToOne(targetEntity="TransferirStock", mappedBy="movimientoOrigen")
     */
    private $origen;

    /**
     * @ORM\Column(name="visible", type="boolean", options={"default":true})
     */
    private $visible; //flag utilizado para indicar si el movimiento debe ser tomado para calculos o para mostrar en los informes (Por ej, las TRX no se muestran, solo se muestran los movimientos de Egreso e Ingreso que se generarn automaticamente)

    /**
     * @ORM\Column(name="eliminado", type="boolean", options={"default":false}, nullable=true)
     */
    private $eliminado = false;

    /**
     * @Assert\IsFalse(
     *     message = "El tipo de movimiento requiere que defina cual articulo se transformara!!"
     * )
     */
    public function isTransformaProductos()
    {
        if ($this->artProcFaena)
        {
            if  ($this->artProcFaena->getConcepto()->getTipoMovimiento()->getTransformaProductos())
            {
                return $this->artProcFaena->getConcepto()->getArticuloOrigenTransformacion() == null;
            }
        }
        return false;
    }


    public function getValorWhitAtribute($atributo, $espejo = false)
    {
        $value = null;
        foreach ($this->valores as $v) 
        {
            //$val = $v->getAtributo()?$v->getAtributo()->getAtributoAbstracto():$v->getAtributoAbstracto();
             $val = $v->getAtributo()?$v->getAtributo()->getAtributoBase():$v->getAtributoAbstracto();
            if ($val->getId() == $atributo)
            {
                if ($v->getAtributo())  //tiene el Atributo Asignado, puede verificar si debe devolver el valor espejo o no
                {
                    if ($v->getAtributo()->getEspejo() == $espejo)
                    {
                        return $v;
                    }
                    else
                    {
                        $value = $v;
                    }
                }
                else
                {
                    return $v;
                }
            }
        }
        return $value;
    }

    public function generateAtributes()
    {

        $atributoConcepto = $this->artProcFaena->getAtributos()->toArray();

        if (!$atributoConcepto)
            throw new \Exception("Error Processing Request", 1);
            
        $atributos = $atributoConcepto; //$atributoConcepto->getAtributos()->toArray();
        uasort($atributos, function ($a, $b) {
                                                  if ($a->getNumeroOrden() == $b->getNumeroOrden()) {
                                                      return 0;
                                                  }
                                                  return ($a->getNumeroOrden() < $b->getNumeroOrden()) ? -1 : 1;
                                              });
        foreach ($atributos as $atributo) {
            if (!$atributo->getEliminado())
                $this->addValore($atributo->getEntityValorAtributo($atributo));
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

    public function setValores(\Doctrine\Common\Collections\ArrayCollection $valores)
    {
        $this->valores = $valores;
    }

    public function getValorConAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo)
    {
        foreach ($this->valores as $value) {
            if ($value->getAtributo()->getAtributoAbstracto() == $atributo){
                return $value;
            }
        }
        return null;
    }

    public function setValorConAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo, $valor)
    {
        foreach ($this->valores as $value) 
        {
            if ($value->getAtributo()->getAtributoAbstracto() == $atributo)
            {
                $value->setValor($valor);
               // throw new \Exception($value." ERROR ".$valor, 1);                
            }
        }
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

    public abstract function updateValues($promedio, $entityManager);

    public abstract function getType();

    public function updateValueAtribute($valor)
    {
        return $valor;

    }

    public function getConceptos($conceptos, $proceso = null){
        
    }

    public function getFactor()
    {
        return 1;
    }

    /**
     * Set artProcFaena
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artProcFaena
     *
     * @return MovimientoStock
     */
    public function setArtProcFaena(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artProcFaena = null)
    {
        $this->artProcFaena = $artProcFaena;

        return $this;
    }

    /**
     * Get artProcFaena
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto
     */
    public function getArtProcFaena()
    {
        return $this->artProcFaena;
    }

    /**
     * Set destino
     *
     * @param \GestionFaenaBundle\Entity\faena\TransferirStock $destino
     *
     * @return MovimientoStock
     */
    public function setDestino(\GestionFaenaBundle\Entity\faena\TransferirStock $destino = null)
    {
        $this->destino = $destino;

        return $this;
    }

    /**
     * Get destino
     *
     * @return \GestionFaenaBundle\Entity\faena\TransferirStock
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * Set origen
     *
     * @param \GestionFaenaBundle\Entity\faena\TransferirStock $origen
     *
     * @return MovimientoStock
     */
    public function setOrigen(\GestionFaenaBundle\Entity\faena\TransferirStock $origen = null)
    {
        $this->origen = $origen;

        return $this;
    }

    /**
     * Get origen
     *
     * @return \GestionFaenaBundle\Entity\faena\TransferirStock
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return MovimientoStock
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @ORM\PrePersist
     */
    public function setVisiblePrePersist()
    {
        $this->updateVisible();
    }

    protected abstract function updateVisible();

    /**
     * Set eliminado
     *
     * @param boolean $eliminado
     *
     * @return MovimientoStock
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

    /**
     * Set faenaDiaria
     *
     * @param \GestionFaenaBundle\Entity\FaenaDiaria $faenaDiaria
     *
     * @return MovimientoStock
     */
    public function setFaenaDiaria(\GestionFaenaBundle\Entity\FaenaDiaria $faenaDiaria = null)
    {
        $this->faenaDiaria = $faenaDiaria;

        return $this;
    }

    /**
     * Get faenaDiaria
     *
     * @return \GestionFaenaBundle\Entity\FaenaDiaria
     */
    public function getFaenaDiaria()
    {
        return $this->faenaDiaria;
    }
}
