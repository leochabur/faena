<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransferirStock
 *
 * @ORM\Table(name="sp_st_trans_st")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\TransferirStockRepository")
 */
class TransferirStock extends MovimientoCompuesto
{


    public function __toString()
    {
        return "Transferir";
    }

    public function getType()
    {
        return 5;
    }

    public function updateValues($promedio, $entityManager, $automatico = false)
    {
        $iterator = $this->getValores()->getIterator();
        $iterator->uasort(function ($first, $second) {
            return (int) $first->getAtributo()->getPosition() > (int) $second->getAtributo()->getPosition() ? 1 : -1;
        });
        
        foreach ($this->getValores() as $valor) 
        {
            $valor->calcularValor($this, $entityManager, $automatico);
        }
    }

    public static function getInstance()
    {
        return 5;
    }

    protected function updateVisible()
    {
        $this->setVisible(false);
    }


}
