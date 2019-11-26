<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use GestionFaenaBundle\Entity\faena\ValorNumerico;
/**
 * AtributoMedible
 *
 * @ORM\Table(name="sp_gst_atr_med")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\AtributoMedibleRepository")
 */
abstract class AtributoMedible extends Atributo
{

    /**
     * @var float
     *
     * @ORM\Column(name="factorAjuste", type="float", nullable=true, options={"default":0.0})
     * @Assert\Type(type="float", message="Tipo de valor invalido.")
     */
    private $factorAjuste = 0;

    /**
    * @ORM\ManyToOne(targetEntity="UnidadMedida") 
    * @ORM\JoinColumn(name="id_unt_med", referencedColumnName="id")
    * @Assert\NotNull(message="En campo no puede permanecer en blanco!")
    */      
    private $unidadMedida;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="ajustable", type="boolean", options={"default":false})
     */
    private $ajustable; 

    /**
     * Set factorAjuste
     *
     * @param float $factorAjuste
     *
     * @return AtributoMedible
     */
    public function setFactorAjuste($factorAjuste)
    {
        $this->factorAjuste = $factorAjuste;

        return $this;
    }

    /**
     * Get factorAjuste
     *
     * @return float
     */
    public function getFactorAjuste()
    {
        return $this->factorAjuste;
    }

    /**
     * Set unidadMedida
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\UnidadMedida $unidadMedida
     *
     * @return AtributoMedible
     */
    public function setUnidadMedida(\GestionFaenaBundle\Entity\gestionBD\UnidadMedida $unidadMedida = null)
    {
        $this->unidadMedida = $unidadMedida;

        return $this;
    }

    /**
     * Get unidadMedida
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\UnidadMedida
     */
    public function getUnidadMedida()
    {
        return $this->unidadMedida;
    }

    /**
     * Set ajustable
     *
     * @param boolean $ajustable
     *
     * @return AtributoMedible
     */
    public function setAjustable($ajustable)
    {
        $this->ajustable = $ajustable;

        return $this;
    }

    /**
     * Get ajustable
     *
     * @return boolean
     */
    public function getAjustable()
    {
        return $this->ajustable;
    }

    public function getTipo()
    {
        return 'Medible';
    }

    public function getAjuste()
    {
        return $this->factorAjuste;
    }

    public function getEntityValorAtributo($atributo)
    {
        $value = new ValorNumerico();
        $value->setUnidadMedida($this->unidadMedida);
        $value->setAtributo($atributo);
        return $value;
    }

    public function getType()
    {
        return 1;
    }
}
