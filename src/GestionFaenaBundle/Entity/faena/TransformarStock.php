<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransformarStock
 *
 */
class TransformarStock extends MovimientoStock
{
    public function __toString()
    {
        return "Transformar";
    }

    public function getType()
    {
        return 4;
    }

    public function updateValues($promedio, $entityManager)
    {





    }

    protected function updateVisible()
    {}

}
