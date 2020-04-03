<?php

namespace GestionFaenaBundle\Repository;

/**
 * ProcesoFaenaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProcesoFaenaRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllProcesos()
    {
        return $this->getEntityManager()
            		->createQuery('SELECT p FROM GestionFaenaBundle:ProcesoFaena p WHERE p.activo = :actvo ORDER BY p.orden')
            		->setParameter('actvo', true)
            		->getResult();
    }

    public function getProcesoInicio()
    {
        return $this->getEntityManager()
            		->createQuery('SELECT p FROM GestionFaenaBundle:ProcesoInicio p WHERE p.activo = :actvo')
            		->setParameter('actvo', true)
            		->getOneOrNullResult();
    }

    public function articlesOfProcess($proceso)
    {
        return $this->getEntityManager()
                    ->createQuery('SELECT apf
                                   FROM GestionFaenaBundle:gestionBD\ArticuloProcesoFaena apf
                                   WHERE apf.proceso = :proceso')
                    ->setParameter('proceso', $proceso)
                    ->getResult();
    }
}
