<?php

namespace GestionFaenaBundle\Entity\gestionBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * Granja
 *
 * @ORM\Table(name="sp_gst_ent_ext_gja")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\gestionBD\GranjaRepository")
 */
class Granja extends EntidadExterna
{

    /**
     * @var bool
     *
     * @ORM\Column(name="propia", type="boolean")
     */
    private $propia;

    /**
     * Set propia
     *
     * @param boolean $propia
     *
     * @return Granja
     */
    public function setPropia($propia)
    {
        $this->propia = $propia;

        return $this;
    }

    /**
     * Get propia
     *
     * @return bool
     */
    public function getPropia()
    {
        return $this->propia;
    }
}
