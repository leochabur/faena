<?php

namespace GestionFaenaBundle\Repository\faena;

/**
 * ProcesoFaenaDiariaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProcesoFaenaDiariaRepository extends \Doctrine\ORM\EntityRepository
{

	//Dado un ProcesoFaena, devuelve el ProcesoFaenaDiaria correspondiente
    public function getProcesoFaenaDiariaWhitProcess(\GestionFaenaBundle\Entity\ProcesoFaena $proceso)
    {
        return $this->getEntityManager()
            		->createQuery('SELECT pfd 
            					  FROM GestionFaenaBundle:faena\ProcesoFaenaDiaria pfd 
            					  WHERE pfd.procesoFaena = :proceso')
            		->setParameter('proceso', $proceso)
            		->getOneOrNullResult();
    }
}
