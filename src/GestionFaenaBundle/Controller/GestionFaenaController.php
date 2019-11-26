<?php

namespace GestionFaenaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GestionFaenaBundle\Form\FaenaDiariaType;
use GestionFaenaBundle\Entity\FaenaDiaria;
use Symfony\Component\HttpFoundation\Request;
use GestionFaenaBundle\Entity\ProcesoFaena;
use GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use GestionFaenaBundle\Repository\FaenaDiariaRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use GestionFaenaBundle\Form\faena\EntradaStockType;
use GestionFaenaBundle\Form\faena\SalidaStockType;
use GestionFaenaBundle\Form\faena\TransformarStockType;
use GestionFaenaBundle\Form\faena\InitMoveStockType;
use GestionFaenaBundle\Entity\faena\EntradaStock;
use GestionFaenaBundle\Entity\faena\SalidaStock;
use GestionFaenaBundle\Entity\faena\TransformarStock;
use GestionFaenaBundle\Entity\faena\MovimientoStock;
use GestionFaenaBundle\Entity\faena\ConceptoMovimiento;
use GestionFaenaBundle\Entity\gestionBD\Granja;
use GestionFaenaBundle\Entity\gestionBD\Transportista;
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use GestionFaenaBundle\Repository\gestionBD\ArticuloProcesoFaenaRepository; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class GestionFaenaController extends Controller
{
    /**
     * @Route("/addFanDay", name="bd_add_faena_diaria")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addFaenaDiariaAction()
    {
        $faena = new FaenaDiaria();
        $form = $this->getFormAddFaenaDiaria($faena);
        return $this->render('@GestionFaena/faenaDiariaABM.html.twig', array('form' => $form->createView()));
    }

    private function getFormAddFaenaDiaria($faena)
    {
        return $this->createForm(FaenaDiariaType::class, $faena, ['action' => $this->generateUrl('bd_add_faena_diaria_procesar'),'method' => 'POST']);
    }

    /**
     * @Route("/addFanDayProc", name="bd_add_faena_diaria_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addFaenaDiariaProcesarAction(Request $request)
    {
        $faena = new FaenaDiaria();
        $form = $this->getFormAddFaenaDiaria($faena);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em = $this->getDoctrine();
            $procesos = $em->getRepository(ProcesoFaena::class)->findAllProcesos();
            foreach ($procesos as $proceso) {
                $procesoFaena = new ProcesoFaenaDiaria($faena, $proceso);
                $faena->addProceso($procesoFaena);
            }
            $em->getManager()->persist($faena);
            $em->getManager()->flush();
        }
        return $this->render('@GestionFaena/faenaDiariaABM.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/index", name="main_page_site")

     */
    public function successAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('@GestionFaena/Default/success.html.twig');
    }


    /**
     * @Route("/viewFan", name="bd_view_faena")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function viewFaenaDiariaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(FaenaDiaria::class);
        $faenas = $repo->findAllFaenas();
        return $this->render('@GestionFaena/faena/listFaenaDiaria.html.twig', array('faenas' => $faenas));
    }

    /**
     * @Route("/viewProcFanDay/{fan}", name="bd_view_procesos_faena_diaria")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function viewProcesosFaenaDiariaAction($fan, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(FaenaDiaria::class);
        $faena = $repo->find($fan);
        return $this->render('@GestionFaena/faena/procesosFaenaDiaria.html.twig', array('faena' => $faena));
    }

    private function getFormSelectFaena()
    {
        $form =    $this->createFormBuilder()
                        ->add('faenas', 
                              EntityType::class, 
                              [
                              'class' => FaenaDiaria::class,
                              'query_builder' => function (FaenaDiariaRepository $er) {
                                                                                         return $er->createQueryBuilder('f')
                                                                                                    ->orderBy('f.fechaFaena', 'ASC');
                                                                                      }
                                ])
                        ->add('load', SubmitType::class, ['label' => 'Cargar Faenas'])      
                        ->getForm();
        return $form;
    }


    private function getFormBeginMovStockAction($proceso, $movimiento = null)
    {
        $proc = $proceso;
        $em = $this->getDoctrine()->getManager();
        return $this->createForm(InitMoveStockType::class, $movimiento, array('manager' => $this->getDoctrine()->getManager()));
    }

    /**
     * @Route("/gstProcFanDay/{proc}", name="bd_adm_proc_fan_day", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function administrarProcesoFaenaDiaria(Request $request, $proc)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $repository = $em->getRepository('GestionFaenaBundle:faena\MovimientoStock');
        $movimientos = $repository->findAllMovimientos($proceso);

        $conceptos = array();
        $movStock = array();
        $datos = array();
        $formsDelete = array();
        $totales = array();
        foreach ($movimientos as $mov){
            $formsDelete[$mov->getId()] = $this->getFormDeleteMovimiento($mov->getId())->createView();
            $movStock[] = $mov->getId();
            $keyMov = array_search($mov->getId(), $movStock);
            $datos[$keyMov] = array();
            foreach ($mov->getValores() as $valor) {   
              if ($valor->getAtributo()->getMostrar())
              {        
                              if (!in_array($valor->getAtributo(), $conceptos)){
                                $conceptos[] = $valor->getAtributo();
                              }
                              $keyConcepto = array_search($valor->getAtributo(), $conceptos);
                              $datos[$keyMov][$keyConcepto] = array('data' => $valor->getData(), 'mov' => $mov->getId(), 'art' => $mov->getArtProcFaena()->getId(), 'proc' => $mov->getProcesoFnDay()->getId());
                              if ($valor->getAtributo()->getAcumula())
                              {
                                if (!isset($totales[$keyConcepto]))
                                {
                                    $totales[$keyConcepto] = array('cant' => 0, 'total' => 0);
                                }
                                if ($valor->getAtributo()->getPromedia())
                                  $totales[$keyConcepto]['cant']++;
                                else
                                  $totales[$keyConcepto]['cant'] = 1;
                                $totales[$keyConcepto]['total']+= $valor->getData(false);
                              }
              }
            }
        }

        uasort($conceptos, function ($a, $b) {
                                                  if ($a->getOrden() == $b->getOrden()) {
                                                      return 0;
                                                  }
                                                  return ($a->getOrden() < $b->getOrden()) ? -1 : 1;
                                              });

       // return new Response(var_dump($movStock));
        
        if ($request->isMethod('post'))
        { 
          $values = $request->request->all();
        //  return new Response(var_dump($values['gestionfaenabundle_faena_initmovest']['movimiento']));
          $clase = $values['gestionfaenabundle_faena_initmovest']['movimiento'];
          $movimiento = new $clase;//$data['movimiento'];
          $form = $this->getFormBeginMovStockAction($proceso, $movimiento);
          $form->handleRequest($request);
          if ($form->isValid())
          {
           // $data = $form->getData();
            
            $movimiento->setProcesoFnDay($proceso);
           // $movimiento->setArtProcFaena($data['articulo']);
           // $movimiento->setConcepto($data['concepto']);
            $movimiento->generateAtributes();
            $formAtr = $this->getFormAddMovStock($movimiento, $proc, $movimiento->getArtProcFaena()->getId());
            return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('movs' => $movStock, 'fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'form' => $form->createView()));
            //return new Response($movimiento);
          }
          return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('totales' =>$totales,'formsDelete' => $formsDelete, 'movs' => $movStock, 'conceptos' => $conceptos, 'datos' => $datos, 'movimientos' => $movimientos, 'proceso' => $proceso, 'form' => $form->createView()));
        }
        $form = $this->getFormBeginMovStockAction($proceso, new EntradaStock());
        return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('totales' =>$totales,'formsDelete' => $formsDelete, 'movs' => $movStock, 'conceptos' => $conceptos, 'datos' => $datos, 'movimientos' => $movimientos, 'proceso' => $proceso, 'form' => $form->createView()));
    }


    /**
     * @Route("/gstMovProc/{proc}/{art}/{conc}/{type}", name="bd_adm_proc_mov_st", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarMovimientoStockAction(Request $request, $proc, $art, $conc, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $articulo = $em->find(ArticuloProcesoFaena::class, $art);
        $concepto = $em->find(ConceptoMovimiento::class, $conc);
        if ($type == 2){
              $movimiento = new EntradaStock();   
              $stock = 0;     
        }
        elseif($type == 3){
              $repo = $em->getRepository(MovimientoStock::class);
              $stock = $repo->pesoPromedio($proceso, $articulo)['valor'];
              $movimiento = new SalidaStock(); 
        }
        elseif($type == 4){
              $repo = $em->getRepository(TransformarStock::class);
             // $stock = $repo->pesoPromedio($proceso, $articulo)['valor'];
              $movimiento = new TransformarStock(); 
        }
        $movimiento->setArtProcFaena($articulo);
        $movimiento->setConcepto($concepto);
        $movimiento->setProcesoFnDay($proceso);
        $movimiento->generateAtributes();
        $formAtr = $this->getFormAddMovStock($movimiento, $proc, $art);
        $formAtr->handleRequest($request);
        $movimiento->updateValues($stock);
        if ($formAtr->isValid())
        {
            $em->persist($movimiento);
            $em->flush();
            return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc]);
        }
        return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso));
        
    }


    /**
     * @Route("/gstFanDayClose/{id}", name="bd_fan_day_close", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function closeFanDayAction($id, Request $request)
    {
      try{
          $entityManager = $this->getDoctrine()->getManager();
          $faenaDiaria = $entityManager->find(FaenaDiaria::class, $id);
          if ($faenaDiaria->getFinalizada())
              return new JsonResponse(array('status' => false, 'msge' => 'La faena ya se encuentra finalizada!!'));
          $faenaDiaria->setFinalizada(true);
          $faenaDiaria->setFechaCierre(new \DateTime());
          $faenaDiaria->setUserClose($this->getUser());
          $entityManager->flush();
          return new JsonResponse(array('status' => true));
        }
        catch (\Exception $e){
                                return new JsonResponse(array('status' => false, 'msge' => $e->getMessage()));
        }
        //return new JsonResponse($atributos);
    }

    /**
     * @Route("/gstFanDayDelete/{id}", name="bd_fan_day_delete", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteFanDayAction($id, Request $request)
    {
      try{
          $entityManager = $this->getDoctrine()->getManager();
          $faenaDiaria = $entityManager->find(FaenaDiaria::class, $id);
          if (!$faenaDiaria->faenaSinMovimientos())
              return new JsonResponse(array('status' => false, 'msge' => 'No es posible eliminar la faena ya que la misma posee movimiento en alguno de sus procesos!!'));
          $entityManager->remove($faenaDiaria);
          $entityManager->flush();
          return new JsonResponse(array('status' => true));
        }
        catch (\Exception $e){
                                return new JsonResponse(array('status' => false, 'msge' => $e->getMessage()));
        }
        //return new JsonResponse($atributos);
    }

    public function getFormSelectArticleProccess($articulos)
    {
        $form = $this->createFormBuilder()
                      ->add('articulos', 
                            EntityType::class, 
                            [
                              'class' => ArticuloProcesoFaena::class,
                              'choices' => $articulos
                            ])
                        ->add('load', SubmitType::class, ['label' => 'Registrar ingreso'])      
                        ->getForm();
        return $form;
    }

    private function getFormAddMovStock(MovimientoStock $movimiento, $proc, $art, $url = 'bd_adm_proc_mov_st')
    {
        if ($movimiento->getType() == 2)
        return $this->createForm(EntradaStockType::class, 
                                 $movimiento, 
                                 ['action' => $this->generateUrl($url, array('type' => $movimiento->getType(), 'proc' => $proc, 'art' => $art, 'conc' => $movimiento->getConcepto()->getId(),'mov' => $movimiento->getId())),'method' => 'POST']);
        elseif ($movimiento->getType() == 3){
                  return $this->createForm(SalidaStockType::class, 
                                 $movimiento, 
                                 ['action' => $this->generateUrl($url, array('type' => $movimiento->getType(), 'proc' => $proc, 'conc' => $movimiento->getConcepto()->getId(), 'art' => $art, 'mov' => $movimiento->getId())),'method' => 'POST']);
        }
        elseif ($movimiento->getType() == 4){
                  return $this->createForm(TransformarStockType::class, 
                                 $movimiento, 
                                 ['action' => $this->generateUrl($url, array('type' => $movimiento->getType(), 'proc' => $proc, 'conc' => $movimiento->getConcepto()->getId(), 'art' => $art, 'mov' => $movimiento->getId())),'method' => 'POST']);
        }
    }



    /**
     * @Route("/gstMovEdit/{mov}/{proc}/{art}", name="bd_adm_mov_st_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarMovimientoStockAction($mov, $proc, $art)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $movimiento = $em->find(MovimientoStock::class, $mov);
        $articulo = $em->find(ArticuloProcesoFaena::class, $art);
        $formAtr = $this->getFormAddMovStock($movimiento, $proc, $art, 'bd_adm_edit_mov_stock_procesar');
        return $this->render('@GestionFaena/faena/editMovStock.html.twig', array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso));        
    }

    /**
     * @Route("/gstMovEditProc/{mov}/{proc}/{art}", name="bd_adm_edit_mov_stock_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarEditMovimientoStockAction(Request $request, $mov, $proc, $art)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $movimiento = $em->find(MovimientoStock::class, $mov);
        $articulo = $em->find(ArticuloProcesoFaena::class, $art);
        $formAtr = $this->getFormAddMovStock($movimiento, $proc, $art,'bd_adm_edit_mov_stock_procesar');
        $formAtr->handleRequest($request);
        $repo = $em->getRepository(MovimientoStock::class);
        $stock = $repo->pesoPromedio($proceso, $articulo)['valor'];
        $movimiento->updateValues($stock);
        if ($formAtr->isValid())
        {
            $proceso->setUltimoMovimiento(new \DateTime());
            $em->flush();
            return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc]);
        }
        return $this->render('@GestionFaena/faena/editMovStock.html.twig', array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso)); 
    }


    private function getFormDeleteMovimiento($mov)
    {
        $form = $this->createFormBuilder()
                      ->add('delete', SubmitType::class, ['label' => 'Eliminar']) 
                      ->setAction($this->generateUrl('bd_adm_mov_st_delete', array('mov' => $mov)))     
                      ->setMethod('DELETE')   
                      ->getForm();
        return $form;
    }
    /**
     * @Route("/gst/{mov}", name="bd_adm_mov_st_delete", methods={"DELETE"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function eliminarMovimientoStockAction($mov)
    {
        $em = $this->getDoctrine()->getManager();
        $movimiento = $em->find(MovimientoStock::class, $mov);
        $id = $movimiento->getProcesoFnDay()->getId();
        $em->remove($movimiento);
        $em->flush();
        return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $id]);
    }
}
