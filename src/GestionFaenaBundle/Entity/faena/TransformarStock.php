<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransformarStock
 *
 * @ORM\Table(name="sp_st_trn_st")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\TransformarStockRepository")
 */
class TransformarStock extends MovimientoStock
{
    public function __toString()
    {
        return "Trsnaformar Stock";
    }

    public function getType()
    {
        return 4;
    }

    public function updateValues($promedio)
    {
     /*   $iterator = $this->getValores()->getIterator();
        $iterator->uasort(function ($first, $second) {
            return (int) $first->getAtributo()->getAtributo()->getPosition() > (int) $second->getAtributo()->getAtributo()->getPosition() ? 1 : -1;
        });
        foreach ($this->getValores() as $valor) {
            $valor->calcularValor($this);
        }*/
    }
}

