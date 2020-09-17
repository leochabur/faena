<?php

namespace GestionFaenaBundle\Repository\gestionBD;

/**
 * SucursalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SucursalRepository extends \Doctrine\ORM\EntityRepository
{
	public function getActivos() 
	{ 
			return $this->createQueryBuilder('s')
						->orderBy('s.valor')
						->getQuery()
						->getResult();
	} 
}
