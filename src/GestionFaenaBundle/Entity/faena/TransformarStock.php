<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransformarStock
 *
 * @ORM\Table(name="sp_st_transformar_st")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\TransformarStockRepository")
 */
class TransformarStock extends MovimientoCompuesto
{
    public function __toString()
    {
        return "Transformar";
    }

    public function getType()
    {
        return 4;
    }

    public function updateValues($promedio, $entityManager, $automatico = false)
    {
        $iterator = $this->getValores()->getIterator();
        $iterator->uasort(function ($first, $second) {
            return (int) $first->getAtributo()->getPosition() > (int) $second->getAtributo()->getPosition() ? 1 : -1;
        });
        foreach ($this->getValores() as $valor) {
            $valor->calcularValor($this, $entityManager, $automatico);
        }
    }

    protected function updateVisible()
    {

        $this->setVisible(false);
    }

}
