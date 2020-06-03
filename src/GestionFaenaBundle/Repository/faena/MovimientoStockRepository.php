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

    public function getAllEntradasStockProceso(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso,
                                               \GestionFaenaBundle\Entity\FaenaDiaria $faena,
                                               \GestionFaenaBundle\Entity\opciones\InformeProceso $informe)
    {
        return $this->getEntityManager()
                ->createQuery('SELECT e
                               FROM GestionFaenaBundle:faena\EntradaStock e
                               JOIN e.artProcFaena apf
                               JOIN apf.concepto cmp
                               WHERE e.procesoFnDay = :proceso AND
                                     e.faenaDiaria = :faenaDiaria AND
                                     e.visible = :visible AND
                                     e.eliminado = :eliminado AND
                                     cmp.concepto in (:conceptos)')
                ->setParameter('proceso', $proceso)
                ->setParameter('faenaDiaria', $faena)
                ->setParameter('conceptos', $informe->getConceptos())
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
                                   JOIN valor.movimiento mov
                                   INNER JOIN valor.atributo atributo
                                   INNER JOIN atributo.atributoAbstracto atributoAbstracto
                                   INNER JOIN atributo.articuloAtrConc articuloAtributoConcepto
                                   INNER JOIN articuloAtributoConcepto.articulo articulo
                                   INNER JOIN articuloAtributoConcepto.concepto conceptoMovimientoProceso
                                   INNER JOIN conceptoMovimientoProceso.procesoFaena procesoFaena 
                                   INNER JOIN procesoFaena.procesosFaenaDiaria procesoDiario                                         
                                   WHERE mov.procesoFnDay = :proceso AND mov.eliminado = :eliminado AND procesoDiario = :proceso AND  articulo = :articulo  AND atributoAbstracto = :atributo
                                   GROUP BY atributo")
                    ->setParameter('proceso', $proceso)
                    ->setParameter('articulo', $articulo)
                    ->setParameter('atributo', $atributo)
                    ->setParameter('eliminado', false)
                    ->getOneOrNullResult();
    }

    public function getPromedioAtributoV2___(\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso, 
                                             \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo, 
                                             \GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto $atributo,
                                             $action)
    {
        $accion = ($action == 's'?"SUM":"AVG");
        return $this->getEntityManager()
                    ->createQuery("SELECT articulo.nombre as art, $accion(valor.valor) as stock
                                   FROM GestionFaenaBundle:faena\ValorNumerico valor
                                   JOIN valor.movimiento mov
                                   JOIN mov.artProcFaena artAtrCon
                                   JOIN artAtrCon.articulo articulo
                                   LEFT JOIN valor.atributoAbstracto atributo
                                   LEFT JOIN valor.atributo atr
                                   WHERE mov.procesoFnDay = :proceso AND  
                                         articulo = :articulo  AND 
                                         (atributo = :atributo OR atr.atributoAbstracto = :atributo) AND 
                                         mov.eliminado = :eliminado
                                   GROUP BY articulo")
                    ->setParameter('proceso', $proceso)
                    ->setParameter('articulo', $articulo)
                    ->setParameter('atributo', $atributo)
                    ->setParameter('eliminado', false)
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
                                   WHERE (atributo.atributoAbstracto = :atrAbs OR valor.atributoAbstracto = :otherAtrAbs) AND movimiento.visible = :visible AND movimiento.procesoFnDay = :proceso AND articulo = :articulo AND movimiento.eliminado = :eliminado
                                   GROUP BY articulo")
                    ->setParameter('proceso', $proceso)
                    ->setParameter('visible', true)
                    ->setParameter('eliminado', false)
                    ->setParameter('articulo', $articulo)
                    ->setParameter('atrAbs', $atributo)
                    ->setParameter('otherAtrAbs', $atributo)
                    ->getOneOrNullResult();//\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function getStockArticulosPorProceso(\GestionFaenaBundle\Entity\ProcesoFaena $proceso)
    /*para un ProcesoFaena, devuelve el stock de cada uno de los articulos del mismo, agrupados por Articulo y por FaenaDiaria, 
      utilizado para el caso de los procesos permanentes para indicar de cuando es cada producto del mismo*/
    {
        return $this->getEntityManager()
                    ->createQuery("SELECT articulo.nombre as nombre, sum(valor.valor) as cantidad, faenaDiaria.fechaFaena as fecha
                                   FROM GestionFaenaBundle:faena\ValorNumerico valor  
                                   JOIN valor.movimiento movimiento 
                                   JOIN movimiento.faenaDiaria faenaDiaria
                                   JOIN movimiento.procesoFnDay procFanDay
                                   JOIN procFanDay.procesoFaena procesoFaena
                                   JOIN procesoFaena.manejosStock manejoStock
                                   JOIN movimiento.artProcFaena artAtrCon
                                   JOIN artAtrCon.articulo articulo                                    
                                   WHERE (valor.atributoAbstracto = manejoStock.atributo) AND
                                         (manejoStock.articulo = articulo) AND 
                                         (procesoFaena = :proceso) AND
                                         (movimiento.visible = :visible) AND
                                         (movimiento.eliminado = :eliminado)
                                   GROUP BY articulo, faenaDiaria")
                    ->setParameter('proceso', $proceso)
                    ->setParameter('visible', true)
                    ->setParameter('eliminado', false)
                    ->getResult();
    }

    public function getStockArticulos (\GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria $proceso,
                                       \GestionFaenaBundle\Entity\gestionBD\Articulo $articulo,
                                       \GestionFaenaBundle\Entity\FaenaDiaria $faena)
    /*para un ProcesoFaenaDiaria devuelve cual es el stock del articulo para la FaenaDiaria*/
    {
        return $this->getEntityManager()
                    ->createQuery("SELECT '' as nombre, sum(valor.valor) as cantidad
                                   FROM GestionFaenaBundle:faena\ValorNumerico valor  
                                   JOIN valor.movimiento movimiento 
                                   JOIN movimiento.procesoFnDay procFanDay
                                   JOIN procFanDay.procesoFaena procesoFaena
                                   JOIN procesoFaena.manejosStock manejoStock
                                   JOIN movimiento.artProcFaena artAtrCon                                  
                                   WHERE (valor.atributoAbstracto = manejoStock.atributo) AND
                                         (manejoStock.articulo = artAtrCon.articulo) AND 
                                         (artAtrCon.articulo = :articulo) AND
                                         (procFanDay = :proceso) AND
                                         (movimiento.visible = :visible) AND
                                         (movimiento.faenaDiaria = :faenaDiaria) AND
                                         (movimiento.eliminado = :eliminado)
                                    GROUP BY artAtrCon.articulo")
                    ->setParameter('proceso', $proceso)
                    ->setParameter('articulo', $articulo)
                    ->setParameter('faenaDiaria', $faena)
                    ->setParameter('visible', true)
                    ->setParameter('eliminado', false)
                    ->getOneOrNullResult();
    }

}
