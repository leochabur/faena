<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValorTexto
 *
 * @ORM\Table(name="sp_st_mov_val_txt")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\ValorTextoRepository")
 */
class ValorTexto extends ValorAtributo
{

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="string", length=255)
     */
    private $valor;

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return ValorTexto
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    public function calcularValor($movimiento, $entityManager, $promedio = 0)
    {
        
    }

    public function getData()
    {
        return $this->getValor();
    }

    public function __toString()
    {
        return $this->valor;
    }
}
