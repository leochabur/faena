<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transportista
 *
 * @ORM\Table(name="sp_gst_ent_ext_trs")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\TransportistaRepository")
 */
class Transportista extends EntidadExterna
{

    /**
     * @var string
     *
     * @ORM\Column(name="cuit", type="string", length=255)
     */
    private $cuit;

    /**
     * Set cuit
     *
     * @param string $cuit
     *
     * @return Transportista
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string
     */
    public function getCuit()
    {
        return $this->cuit;
    }
}
