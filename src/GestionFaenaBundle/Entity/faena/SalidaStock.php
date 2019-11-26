<?php

namespace GestionFaenaBundle\Entity\faena;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalidaStock
 *
 * @ORM\Table(name="sp_st_sal_st")
 * @ORM\Entity(repositoryClass="GestionFaenaBundle\Repository\faena\SalidaStockRepository")
 */
class SalidaStock extends MovimientoStock
{
	public function getType()
	{
		return 3;
	}

    public function updateValues($promedio)
    {
        $iterator = $this->getValores()->getIterator();
        $iterator->uasort(function ($first, $second) {
            return (int) $first->getAtributo()->getAtributo()->getPosition() > (int) $second->getAtributo()->getAtributo()->getPosition() ? 1 : -1;
        });
        foreach ($this->getValores() as $valor) {
            $valor->calcularValor($this, $promedio);
        }
    }

    public function getConceptos($conceptos){
        $collections = array();
        foreach ($conceptos as $con) {
           if ($con->getEsa() == 6)
            $collections[] = $con;
        }
        return $collections;
    }

    public function updateValueAtribute($valor)
    {
        return (-1)*$valor;

    }
}
