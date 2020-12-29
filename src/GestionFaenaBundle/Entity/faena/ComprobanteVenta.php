<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComprobanteVenta
 *
 * @ORM\Table(name="sp_st_cbte_vta_st")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ComprobanteVentaRepository")
 */
class ComprobanteVenta extends MovimientoStock
{

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmado", type="boolean")
     */
    private $confirmado;

    protected function updateVisible()
    {

    }
    public function updateValues($promedio, $entityManager, $automatico = false)
    {

    }

    public function getType()
    {

    }

    /**
     * Set confirmado
     *
     * @param boolean $confirmado
     *
     * @return ComprobanteVenta
     */
    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;

        return $this;
    }

    /**
     * Get confirmado
     *
     * @return bool
     */
    public function getConfirmado()
    {
        return $this->confirmado;
    }
}

