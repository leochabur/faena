<?php

namespace GestionFaenaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProcesoFin
 *
 * @ORM\Table(name="sp_proc_end")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\ProcesoFinRepository")
 */
class ProcesoFin extends ProcesoFaena
{

    public function getType()
    {
        return "Proceso Fin";
    }

}

