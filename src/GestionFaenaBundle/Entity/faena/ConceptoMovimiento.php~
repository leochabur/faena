<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ConceptoMovimiento
 *
 * @ORM\Table(name="sp_st_con_mov")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ConceptoMovimientoRepository")
 */
class ConceptoMovimiento
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
     * @ORM\Column(name="concepto", type="string", length=255)
     * @Assert\NotNull(message="El campo no puede permanecer en blanco!")
     */
    private $concepto;

    /**
     * @ORM\Column(name="transforma", type="boolean", nullable=true, options={"default" : false})
     */
    private $transforma;

    public function getAtributoConcepto(\GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto $apf)
    {
        foreach ($this->atributos as $atr) {
            if ($atr->getArticulo() === $apf)
                return $atr;
        }
        return null;
    }

    public function manejaAtributo(\GestionFaenaBundle\Entity\gestionBD\AtributoProceso $atributo)
    {
        foreach ($this->atributos as $atributoConcepto) {
            if ($atributoConcepto->existeAtributo($atributo))
                return true;
        }
        return false;
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
     * Set concepto
     *
     * @param string $concepto
     *
     * @return ConceptoMovimiento
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return string
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    public function __toString()
    {
        return $this->concepto;
    }


    /**
     * Set transforma
     *
     * @param boolean $transforma
     *
     * @return ConceptoMovimiento
     */
    public function setTransforma($transforma)
    {
        $this->transforma = $transforma;

        return $this;
    }

    /**
     * Get transforma
     *
     * @return boolean
     */
    public function getTransforma()
    {
        return $this->transforma;
    }
}
