<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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

    public function calcularValor($movimiento, $entityManager, $automatico = false)
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

    public function isValid()
    {
        if (!$this->valor)
        {
            return ['ok' => false, 'message' => 'El campo '.$this->getAtributo().' no puede permanecer en blanco!'];
        }
        return ['ok' => true];
    }

    public function isNumeric()
    {
        return false;
    }
}
