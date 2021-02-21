<?php

namespace GestionVentasBundle\Entity\options;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpcionVenta
 *
 * @ORM\Table(name="options_opcion_venta")
 * @ORM\Entity(repositoryClass="GestionVentasBundle\Repository\options\OpcionVentaRepository")
 */
class OpcionVenta
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
