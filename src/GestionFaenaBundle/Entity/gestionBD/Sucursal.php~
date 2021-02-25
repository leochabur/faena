<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sucursal
 *
 * @ORM\Table(name="sp_gst_ent_ext_suc")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\SucursalRepository")
 */
class Sucursal extends EntidadExterna
{

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    protected function getIsCliente()
    {
        return true;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Sucursal
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }
}
