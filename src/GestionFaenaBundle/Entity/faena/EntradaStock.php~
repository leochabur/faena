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
		return "Ingreso";
	}

	public function getType()
	{
		return 2;
	}

    public function updateValues($promedio, $entityManager)
    {
        $iterator = $this->getValores()->getIterator();
        $iterator->uasort(function ($first, $second) {
            return (int) $first->getAtributo()->getPosition() > (int) $second->getAtributo()->getPosition() ? 1 : -1;
        });
        foreach ($this->getValores() as $valor) {
            $valor->calcularValor($this, $entityManager, 0);
        }
    }

    public function getConceptos($conceptos, $proceso = null){
        if ($proceso)
            $conceptosProceso = $proceso->getProcesoFaena()->getConceptos();
        else
            $conceptosProceso = $this->getProcesoFnDay()->getProcesoFaena()->getConceptos();
        $collections = array();
        foreach ($conceptos as $con) {
         //  if (in_array($con->getEsa(), array(1,3,7,5)))
          //      if ($conceptosProceso->contains($con))
                    $collections[] = $con;
        }
        return $collections;
    }

    public static function getInstance()
    {
        return 2;
    }

    protected function updateVisible()
    {
        $this->setVisible(true);
    }


}
