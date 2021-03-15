<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;
use GestionFaenaBundle\Entity\faena\EntradaStock;
/**
 * ProcesoFaenaDiaria
 *
 * @ORM\Table(name="sp_proc_fan_day")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ProcesoFaenaDiariaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProcesoFaenaDiaria
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
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena", inversedBy="procesosFaenaDiaria") 
    * @ORM\JoinColumn(name="id_proc_fan", referencedColumnName="id")
    */      
    private $procesoFaena;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ultimoMovimiento", type="datetime", nullable=true)
     */
    private $ultimoMovimiento;

    /**
     * @ORM\OneToMany(targetEntity="MovimientoStock", mappedBy="procesoFnDay")
     * @ORM\OrderBy({"fechaAlta" = "DESC"})
     */
    private $movimientos;

    /**
     * @ORM\ManyToMany(targetEntity="GestionFaenaBundle\Entity\ProcesoFaena")
     * @ORM\JoinTable(name="sp_mov_aut_by_pfd",
     *      joinColumns={@ORM\JoinColumn(name="id_proc", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_mov", referencedColumnName="id")}
     *      )
     */
    private $movimientosRealizados; //almacena los movimientos realizados en el proceso diario, para no poder ejecutar dos veces el mismo movimiento

    /**
     * @ORM\Column(name="pesoPromedio", type="float", nullable=true)
     */
    private $pesoPromedio; //almacena el ultimo peso promedio generado
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->procesoFaena->getNombre();
    }

    public function __construct($proceso)
    {
        $this->procesoFaena = $proceso;
    }

    /**
     * Set procesoFaena
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $procesoFaena
     *
     * @return ProcesoFaenaDiaria
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
     * Set ultimoMovimiento
     *
     * @param \DateTime $ultimoMovimiento
     *
     * @return ProcesoFaenaDiaria
     */
    public function setUltimoMovimiento($ultimoMovimiento)
    {
        $this->ultimoMovimiento = $ultimoMovimiento;

        return $this;
    }

    /**
     * Get ultimoMovimiento
     *
     * @return \DateTime
     */
    public function getUltimoMovimiento()
    {
        return $this->ultimoMovimiento;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate 
     */
    public function updateActionPre()
    {
        $this->ultimoMovimiento = new \DateTime();
    }

    /**
     * Add movimiento
     *
     * @param \GestionFaenaBundle\Entity\faena\MovimientoStock $movimiento
     *
     * @return ProcesoFaenaDiaria
     */
    public function addMovimiento(\GestionFaenaBundle\Entity\faena\MovimientoStock $movimiento)
    {
        $this->movimientos[] = $movimiento;

        return $this;
    }

    /**
     * Remove movimiento
     *
     * @param \GestionFaenaBundle\Entity\faena\MovimientoStock $movimiento
     */
    public function removeMovimiento(\GestionFaenaBundle\Entity\faena\MovimientoStock $movimiento)
    {
        $this->movimientos->removeElement($movimiento);
    }

    /**
     * Get movimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientos()
    {
        return $this->movimientos;
    }

    public function getStockArticulo(\GestionFaenaBundle\Entity\FaenaDiaria $faena, 
                                     \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo,
                                     \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo)
    //dados los parametros recupera cual es el stock del articulo recorriendo todos los movimientos correspondoientes asociados a la faena
    {
       // throw new \Exception("Error Processing Request ".$atributo." ".$articulo, 1);
        
        $stock = 0;
        foreach ($this->movimientos as $mov) 
        {
            if ((!$mov->getEliminado()) && ($mov->getVisible()))
            {
                    if ($mov->getArtProcFaena()->getArticulo() == $articulo)
                    {      
                     //   $manejo = $this->getProcesoFaena()->existeArticuloDefinidoManejoStock($articulo);                            
                      //  if ($manejo)
                      //  {                                
                            //$valor = $mov->getValorWhitAtribute($manejo->getAtributo());
                            $valor = $mov->getValorWhitAtribute($atributo);//$manejo->getAtributo());
                            if ($valor)
                            {
                                $stock+=  $valor->getData(false);
                            }
                      //  }
                    
                    }
            }
        }
        return $stock;
    }

    public function getStockArticuloConFaenaDiaria(\GestionFaenaBundle\Entity\FaenaDiaria $faena, 
                                                     \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo,
                                                     \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo)
    //dados los parametros recupera cual es el stock del articulo recorriendo todos los movimientos correspondoientes asociados a la faena
    {
       // throw new \Exception("Error Processing Request ".$atributo." ".$articulo, 1);
        
        $stock = 0;
        foreach ($this->movimientos as $mov) 
        {
            if ((!$mov->getEliminado()) && ($mov->getVisible()) && ($mov->getFaenaDiaria() == $faena))
            {
                    if ($mov->getArtProcFaena()->getArticulo() == $articulo)
                    {      
                        $valor = $mov->getValorWhitAtribute($atributo);//$manejo->getAtributo());
                        if ($valor)
                        {
                            $stock+=  $valor->getData(false);
                        }
                
                    }
            }
        }
        return $stock;
    }


    public function getMovimientosArticulo(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo,
                                           \GestionFaenaBundle\Entity\FaenaDiaria $faena = null)
    //dados los parametros recupera todos los movimientos para el articulo dado
    {
        $movimientos = [];
        foreach ($this->movimientos as $mov) 
        {
            if ((!$mov->getEliminado()) && ($mov->getVisible()))
            {
                    if ((get_class($mov) == EntradaStock::class) && ($mov->getArtProcFaena()->getArticulo() == $articulo))
                    {      
                        if (!$faena) //no importa de que faena es
                        {
                            return $mov;
                        }
                        else
                        {
                            if ($mov->getFaenaDiaria()->getId() == $faena->getId())
                            {
                                return $mov;
                            }
                        }
                    }
            }
        }
        return null;
    }

    public function getStockArticuloConAtributo(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo, 
                                                \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo,
                                                $soloIngreso,
                                                $accion,
                                                \GestionFaenaBundle\Entity\gestionBD\FactorCalculo $factor = null)
    //dados los parametros recupera cual es el stock del articulo recorriendo todos los movimientos correspondoientes asociados a la faena
    {
        $stock = 0;
        $count = 0;
        foreach ($this->movimientos as $mov) 
        {
            if ((!$mov->getEliminado()) && ($mov->getVisible()))
            {
                if ($mov->getArtProcFaena()->getArticulo() == $articulo)
                {      
                    $valor = $mov->getValorWhitAtribute($atributo);
                    if ($accion == 'U')
                        return $valor->getData();
                    if ($valor)
                    {
                        if ((!$factor) || (!$factor->getConceptosExcentos()->contains($mov->getArtProcFaena()->getConcepto()->getConcepto())))
                        {
                            if($soloIngreso)//solo debe levantar los movimientos de Ingreso de Stock
                            {
                                if (get_class($mov) === EntradaStock::class)
                                {
                                        $stock+=  $valor->getData(false);     
                                        $count++;    
                                      //  throw new \Exception("Pedazo de verga");                       
                                }
                            }
                            else
                            {
                                $stock+=  $valor->getData(false);   
                                $count++;  
                            }
                        }
                    }
                }
            }
        }
        if ($accion == 'P')
            $stock = ($stock/$count);
        return $stock;
    }

    public function realizoMovimientoConArticuloAtriutoConcepto(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $artAtrCon)
    //dado un ArticuloAtributoConcepto veriica si el mismo ya se ha realizado, esta accion se utiliza para no realizar dos veces el movimiento cuando se realizan de manera automatica
    {
        foreach ($this->movimientos as $mov)
        {
            if (!$mov->getEliminado())
            {
                if ($mov->getArtProcFaena() == $artAtrCon)
                {      
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Set pesoPromedio
     *
     * @param float $pesoPromedio
     *
     * @return ProcesoFaenaDiaria
     */
    public function setPesoPromedio($pesoPromedio)
    {
        $this->pesoPromedio = $pesoPromedio;

        return $this;
    }

    /**
     * Get pesoPromedio
     *
     * @return float
     */
    public function getPesoPromedio()
    {
        return $this->pesoPromedio;
    }

    /**
     * Add movimientosRealizado
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $movimientosRealizado
     *
     * @return ProcesoFaenaDiaria
     */
    public function addMovimientosRealizado(\GestionFaenaBundle\Entity\ProcesoFaena $movimientosRealizado)
    {
        $this->movimientosRealizados[] = $movimientosRealizado;

        return $this;
    }

    /**
     * Remove movimientosRealizado
     *
     * @param \GestionFaenaBundle\Entity\ProcesoFaena $movimientosRealizado
     */
    public function removeMovimientosRealizado(\GestionFaenaBundle\Entity\ProcesoFaena $movimientosRealizado)
    {
        $this->movimientosRealizados->removeElement($movimientosRealizado);
    }

    /**
     * Get movimientosRealizados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosRealizados()
    {
        return $this->movimientosRealizados;
    }
}
