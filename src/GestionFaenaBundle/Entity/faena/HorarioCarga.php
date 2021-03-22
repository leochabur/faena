<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * HorarioCarga
 *
 * @ORM\Table(name="sp_st_cbte_vta_hr_cga")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\HorarioCargaRepository")
 */
class HorarioCarga
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
     * @var string
     *
     * @ORM\Column(name="horario", type="string", length=255, unique=true)
     */
    private $horario;

    public function __toString()
    {
        return $this->horario;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set horario
     *
     * @param string $horario
     *
     * @return HorarioCarga
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return string
     */
    public function getHorario()
    {
        return $this->horario;
    }
}

