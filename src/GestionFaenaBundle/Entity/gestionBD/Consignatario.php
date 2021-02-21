<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consignatario
 *
 * @ORM\Table(name="sp_gst_ent_ext_consig")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\ConsignatarioRepository")
 */
class Consignatario extends EntidadExterna
{

    public function __toString()
    {
        return $this->getValor();
    }
    
    protected function getIsCliente()
    {
        return true;
    }
}

