<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * AtributoMedibleAutomatico
 *
 * @ORM\Table(name="sp_gst_atr_med_auto")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\AtributoMedibleAutomaticoRepository")
 */
class AtributoMedibleAutomatico extends AtributoMedible
{
    /**
    * @ORM\ManyToOne(targetEntity="AtributoMedible") 
    * @ORM\JoinColumn(name="id_factor_1", referencedColumnName="id")
     */
    private $factor1;

    /**
    * @ORM\ManyToOne(targetEntity="AtributoMedible") 
    * @ORM\JoinColumn(name="id_factor_2", referencedColumnName="id", nullable=true)
     */
    private $factor2;

    /** 
    * @ORM\Column(type="string", columnDefinition="ENUM('+', '-', '*', '/')") 
    */
    private $operacion;
    

    /**
     * Set operacion
     *
     * @param string $operacion
     *
     * @return AtributoMedibleAutomatico
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return string
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    public function getCarga()
    {
        return 'Automatico';
    }

    public function getCalculo()
    {
        $formula = $this->factor1;
        if ($this->factor2)
                $formula.=$this->operacion.$this->factor2;
        else
            if ($this->getFactorAjuste()){
                $formula.=$this->getOperacion().$this->getFactorAjuste();
            }
        return $formula;
    }

    public function getManual()
    {
        return true;
    }

    public function getFactoresCalculo()
    {
        $factores = array('ajustable' => $this->getAjustable(), 'factor' => $this->getFactorAjuste(), 'operacion' => $this->getOperacion(), 'factores' => array());

        $factores['factores'][1] = $this->factor1;
        if ($this->factor2)
            $factores['factores'][2] = $this->factor2;
        return $factores;
    }

    public function getPosition()
    {
        if ($this->getAjustable())
            return 2;
        else
            return 1;
    }

    public function getClass()
    {
        return 4;
    }


    /**
     * Set factor1
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoMedible $factor1
     *
     * @return AtributoMedibleAutomatico
     */
    public function setFactor1(\GestionFaenaBundle\Entity\gestionBD\AtributoMedible $factor1 = null)
    {
        $this->factor1 = $factor1;

        return $this;
    }

    /**
     * Get factor1
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\AtributoMedible
     */
    public function getFactor1()
    {
        return $this->factor1;
    }

    /**
     * Set factor2
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\AtributoMedible $factor2
     *
     * @return AtributoMedibleAutomatico
     */
    public function setFactor2(\GestionFaenaBundle\Entity\gestionBD\AtributoMedible $factor2 = null)
    {
        $this->factor2 = $factor2;

        return $this;
    }

    /**
     * Get factor2
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\AtributoMedible
     */
    public function getFactor2()
    {
        return $this->factor2;
    }
}
