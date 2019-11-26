<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntradaStock
 *
 * @ORM\Table(name="sp_st_ent_st")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\EntradaStockRepository")
 */
class EntradaStock extends MovimientoStock
{

	public function __toString()
	{
		return "Entrada Stock";
	}

	public function getType()
	{
		return 2;
	}

    public function updateValues($promedio)
    {
        $iterator = $this->getValores()->getIterator();
        $iterator->uasort(function ($first, $second) {
            return (int) $first->getAtributo()->getAtributo()->getPosition() > (int) $second->getAtributo()->getAtributo()->getPosition() ? 1 : -1;
        });
        foreach ($this->getValores() as $valor) {
            $valor->calcularValor($this);
        }
    }

    public function getConceptos($conceptos){
        $collections = array();
        foreach ($conceptos as $con) {
           if ($con->getEsa() == 1)
            $collections[] = $con;
        }
        return $collections;
    }
}
