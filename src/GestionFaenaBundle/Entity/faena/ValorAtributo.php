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
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\Atributo", inversedBy="valoresAtributos") 
    * @ORM\JoinColumn(name="id_atributo", referencedColumnName="id")
    */      
    private $atributo;

    /**
     * @ORM\ManyToOne(targetEntity="MovimientoStock", inversedBy="valores")
     * @ORM\JoinColumn(name="id_mv_st", referencedColumnName="id")
     */
    private $movimiento;

    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto") 
    * @ORM\JoinColumn(name="id_atributo_abstracto", referencedColumnName="id")
    */      
    private $atributoAbstracto;

    /**
     *
     * @ORM\Column(name="posicion", type="integer", options={"default":0}, nullable=true)
     */
    private $posicion;

    /**
     *
     * @ORM\Column(name="mostrar", type="boolean", options={"default":true}, nullable=true)
     */
    private $mostrar;

 /*   public function __toString()
    {
        return ($this->atributo?$this->atributo->getNombre():'SN');
    }
*/
    public abstract function isValid();

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

    public abstract function calcularValor($movimiento, $entityManager, $promedio = 0);

    public abstract function getData();

    /**
     * Set atributo
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\Atributo $atributo
     *
     * @return ValorAtributo
     */
    public function setAtributo(\GestionFaenaBundle\Entity\gestionBD\Atributo $atributo = null)
    {
        $this->atributo = $atributo;

        return $this;
    }

    /**
     * Get atributo
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\Atributo
     */
    public function getAtributo()
    {
        return $this->atributo;
    }

    /**
     * Set atributoAbstracto
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributoAbstracto
     *
     * @return ValorAtributo
     */
    public function setAtributoAbstracto(\GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributoAbstracto = null)
    {
        $this->atributoAbstracto = $atributoAbstracto;

        return $this;
    }

    /**
     * Get atributoAbstracto
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto
     */
    public function getAtributoAbstracto()
    {
        return $this->atributoAbstracto;
    }

    /**
     * Set posicion
     *
     * @param integer $posicion
     *
     * @return ValorAtributo
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return integer
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set mostrar
     *
     * @param boolean $mostrar
     *
     * @return ValorAtributo
     */
    public function setMostrar($mostrar)
    {
        $this->mostrar = $mostrar;

        return $this;
    }

    /**
     * Get mostrar
     *
     * @return boolean
     */
    public function getMostrar()
    {
        return $this->mostrar;
    }
}
