<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * ValorExterno
 *
 * @ORM\Table(name="sp_st_mov_val_ext")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ValorExternoRepository")
 */
class ValorExterno extends ValorAtributo
{
    /**
    * @ORM\ManyToOne(targetEntity="GestionFaenaBundle\Entity\gestionBD\EntidadExterna") 
    * @ORM\JoinColumn(name="id_etntity_extern", referencedColumnName="id")
    * @Assert\NotNull(message="El valor no puede permanecer en Blanco!!")
    */      
    private $entidadExterna;


    public function getDataValue()
    {
        return $this->entidadExterna;
    }

    /**
     * Set entidadExterna
     *
     * @param \GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidadExterna
     *
     * @return ValorExterno
     */
    public function setEntidadExterna(\GestionFaenaBundle\Entity\gestionBD\EntidadExterna $entidadExterna = null)
    {
        $this->entidadExterna = $entidadExterna;

        return $this;
    }

    /**
     * Get entidadExterna
     *
     * @return \GestionFaenaBundle\Entity\gestionBD\EntidadExterna
     */
    public function getEntidadExterna()
    {
        return $this->entidadExterna;
    }
    
    public function calcularValor($movimiento, $entityManager, $automatico = false)
    {
        
    }

    public function getData()
    {
        return $this->entidadExterna.'';
    }

    public function __toString()
    {
        return $this->entidadExterna."";
    }

    public function isValid()
    {
        if (!$this->entidadExterna)
        {
            return ['ok' => false, 'message' => 'El campo '.$this->getAtributo().' no puede permanecer en blanco!'];
        }
        return ['ok' => true];
    }

    public function isNumeric()
    {
        return false;
    }
}
