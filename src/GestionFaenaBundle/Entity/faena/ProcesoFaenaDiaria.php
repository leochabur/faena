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
     */
    private $movimientos;
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
                                        $stock+=  $valor->getData();     
                                        $count++;                           
                                }
                            }
                            else
                            {
                                $stock+=  $valor->getData();   
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
}
