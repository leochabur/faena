<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValorAtributo
 *
 * @ORM\Table(name="sp_val_atr_art_proc_fan")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ValorAtributoRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({1:"ValorAtributo",2:"ValorNumerico", 3:"ValorTexto", 4:"ValorExterno"})
 */
abstract class ValorAtributo
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
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoProceso") 
    * @ORM\JoinColumn(name="id_atributo", referencedColumnName="id")
    */      
    private $atributo;

    /**
     * @ORM\ManyToOne(targetEntity="MovimientoStock", inversedBy="valores")
     * @ORM\JoinColumn(name="id_mv_st", referencedColumnName="id")
     */
    private $movimiento;


 /*   public function __toString()
    {
        return ($this->atributo?$this->atributo->getNombre():'SN');
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
     * Set movimiento
     *
     * @param \GestionFaenaBundle\Entity\faena\MovimientoStock $movimiento
     *
     * @return ValorAtributo
     */
    public function setMovimiento(\GestionFaenaBundle\Entity\faena\MovimientoStock $movimiento = null)
    {
        $this->movimiento = $movimiento;

        return $this;
    }

    /**
     * Get movimiento
     *
     * @return \GestionFaenaBundle\Entity\faena\MovimientoStock
     */
    public function getMovimiento()
    {
        return $this->movimiento;
    }

    public abstract function calcularValor($movimiento, $promedio = 0);

    public abstract function getData();

    /**
     * Set atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo
     *
     * @return ValorAtributo
     */
    public function setAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo = null)
    {
        $this->atributo = $atributo;

        return $this;
    }

    /**
     * Get atributo
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\AtributoProceso
     */
    public function getAtributo()
    {
        return $this->atributo;
    }
}
