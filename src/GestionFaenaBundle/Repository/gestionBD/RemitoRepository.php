<?php

namespace GestionFaenaBundle\Repository\gestionBD;

/**
 * RemitoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RemitoRepository extends \Doctrine\ORM\EntityRepository
{
	public function getActivos() 
	{ 
			return $this->createQueryBuilder('s')
						->orderBy('s.valor')
						->getQuery()
						->getResult();
	} 
}