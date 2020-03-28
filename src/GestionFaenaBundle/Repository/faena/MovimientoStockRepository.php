<?php

namespace GestionFaenaBundle\Repository\faena;

/**
 * MovimientoStockRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovimientoStockRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllMovimientos($proceso)
    {
        return $this->getEntityManager()
            		->createQuery('SELECT p FROM GestionFaenaBundle:faena\MovimientoStock p WHERE p.procesoFnDay = :proceso AND p.visible = :visible AND p.eliminado = :eliminado')
            		->setParameter('proceso', $proceso)
                ->setParameter('visible', true)
                ->setParameter('eliminado', false)
            		->getResult();
    }

    public function pesoPromedio($proceso, $articulo)
    {
        return $this->getEntityManager()
            		->createQuery('SELECT AVG(vn.valor) as valor
            					   FROM GestionFaenaBundle:faena\MovimientoStock p 
            					   JOIN p.valores val
            					   JOIN GestionFaenaBundle:faena\ValorNumerico vn WITH vn.id = val.id
            					   JOIN vn.atributo atr
            					   JOIN atr.atributo a
                                   JOIN a.atributoAbstracto aa
            					   WHERE p.procesoFnDay = :proceso AND aa.atributo = :nombre AND p.artProcFaena = :articulo')
            		->setParameter('proceso', $proceso)
            		->setParameter('articulo', $articulo)
            		->setParameter('nombre', 'Promedio')
            		->getOneOrNullResult();
    }

    public function getPromedioAtributo(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso, 
                                        \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo, 
                                        \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo,
                                        $action)
    {
        $accion = ($action == 's'?"SUM":"AVG");
        return $this->getEntityManager()
                    ->createQuery("SELECT articulo.nombre as art, $accion(valor.valor) as stock
                                   FROM GestionFaenaBundle:faena\ValorNumerico valor   
                                   JOIN valor.atributo atributo
                                   JOIN atributo.atributoAbstracto atributoAbstracto
                                   JOIN atributo.articuloAtrConc articuloAtributoConcepto
                                   JOIN articuloAtributoConcepto.articulo articulo
                                   JOIN articuloAtributoConcepto.concepto conceptoMovimientoProceso
                                   JOIN conceptoMovimientoProceso.procesoFaena procesoFaena 
                                   JOIN procesoFaena.procesosFaenaDiaria procesoDiario                                         
                                   WHERE procesoDiario = :proceso AND  articulo = :articulo  AND atributoAbstracto = :atributo
                                   GROUP BY articulo")
                    ->setParameter('proceso', $proceso)
                    ->setParameter('articulo', $articulo)
                    ->setParameter('atributo', $atributo)
                    ->getOneOrNullResult();
    }

    public function getStockDeArticulosPorProceso(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso)
    {
        return $this->getEntityManager()
                    ->createQuery("SELECT articulo.nombre as art, sum(valor.valor) as stock
                                   FROM GestionFaenaBundle:faena\ValorNumerico valor   
                                   JOIN valor.atributo atributo
                                   JOIN atributo.atributoAbstracto atributoAbstracto
                                   JOIN atributo.articuloAtrConc articuloAtributoConcepto
                                   JOIN articuloAtributoConcepto.articulo articulo
                                   JOIN articuloAtributoConcepto.concepto conceptoMovimientoProceso
                                   JOIN conceptoMovimientoProceso.procesoFaena procesoFaena 
                                   JOIN procesoFaena.manejosStock manejoStock
                                   JOIN procesoFaena.procesosFaenaDiaria procesoDiario                                         
                                   WHERE procesoDiario = :proceso AND manejoStock.articulo = articulo AND manejoStock.atributo = atributoAbstracto
                                   GROUP BY articulo")
                    ->setParameter('proceso', $proceso)
                    ->getResult();
    }

    public function getStockDeArticulo(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso,
                                       \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo,
                                       \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo)
    {
        return $this->getEntityManager()
                    ->createQuery("SELECT articulo.nombre as art, sum(valor.valor) as stock
                                   FROM GestionFaenaBundle:faena\ValorNumerico valor  
                                   JOIN valor.movimiento movimiento 
                                   JOIN movimiento.artProcFaena artAtrCon
                                   JOIN artAtrCon.articulo articulo
                                   LEFT JOIN valor.atributo atributo                                        
                                   WHERE (atributo.atributoAbstracto = :atrAbs OR valor.atributoAbstracto = :otherAtrAbs) AND movimiento.visible = :visible AND movimiento.procesoFnDay = :proceso AND articulo = :articulo
                                   GROUP BY articulo")
                    ->setParameter('proceso', $proceso)
                    ->setParameter('visible', true)
                    ->setParameter('articulo', $articulo)
                    ->setParameter('atrAbs', $atributo)
                    ->setParameter('otherAtrAbs', $atributo)
                    ->getOneOrNullResult();//\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
}
