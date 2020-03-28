<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

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
    */      
    private $entidadExterna;

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
    
    public function calcularValor($movimiento, $entityManager, $promedio = 0)
    {
        
    }

    public function getData()
    {
        return $this->entidadExterna.'';
    }
}
