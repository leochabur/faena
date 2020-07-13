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
use GestionFaenaBundle\Form\faena\TransferirStockType;
use GestionFaenaBundle\Form\faena\InitMoveStockType;
use GestionFaenaBundle\Form\faena\ConceptoMovimientoType;
use GestionFaenaBundle\Entity\faena\EntradaStock;
use GestionFaenaBundle\Entity\faena\SalidaStock;
use GestionFaenaBundle\Entity\faena\TransformarStock;
use GestionFaenaBundle\Entity\faena\TransferirStock;
use GestionFaenaBundle\Entity\faena\MovimientoStock;
use GestionFaenaBundle\Entity\faena\MovimientoCompuesto;
use GestionFaenaBundle\Entity\faena\ConceptoMovimiento;
use GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso;
use GestionFaenaBundle\Entity\faena\ValorNumerico;
use GestionFaenaBundle\Entity\gestionBD\Granja;
use GestionFaenaBundle\Entity\gestionBD\Articulo;
use GestionFaenaBundle\Entity\gestionBD\Transportista;
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto;
use GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto;
use GestionFaenaBundle\Repository\gestionBD\ArticuloProcesoFaenaRepository; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use GestionFaenaBundle\Entity\faena\TipoMovimientoConcepto;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validation;

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
            $em = $this->getDoctrine()->getManager();

            $faena->setUserCreate($this->getUser());
            $procesos = $em->getRepository(ProcesoFaena::class)->findAllProcesos();
            $repository = $em->getRepository(ProcesoFaenaDiaria::class);
            foreach ($procesos as $proceso) 
            {
                if ($proceso->getPermanente()) //el proceso se instancia una unica vez, se debe verificar si existe, sino se crea
                {
                    $procesoFaena = $repository->getProcesoFaenaDiariaWhitProcess($proceso);
                    if (!$procesoFaena)
                    {
                      $procesoFaena = new ProcesoFaenaDiaria($proceso);
                    }
                }
                else
                {
                    $procesoFaena = new ProcesoFaenaDiaria($proceso);
                }

                $faena->addProceso($procesoFaena);
            }
            $em->persist($faena);
            $em->flush();
            $this->addFlash(
                          'sussecc',
                          'Faena generada exitosamente!'
                      );
            return $this->redirectToRoute('bd_view_faena');
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
        $procesos = array();
        $user = $this->getUser();
        $procesosFd = array();
        foreach ($faena->getProcesos() as $p) {
            $procesosFd[] = $p;
        }
        uasort($procesosFd, function ($a, $b) {
                                                  return ($a->getProcesoFaena()->getOrden() < $b->getProcesoFaena()->getOrden()) ? -1 : 1;
                                            });
        foreach ($procesosFd as $proc) 
        {
            if ($user->getProcesos()->contains($proc->getProcesoFaena()))
            {
                $procesos[] = $proc;
            }
        }
        return $this->render('@GestionFaena/faena/procesosFaenaDiaria.html.twig', array('procesos' => $procesos, 'faena' => $faena));
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


    private function getFormBeginMovStockAction(ProcesoFaenaDiaria $proceso, $fd)
    {

        $form = $this->createFormBuilder()
                      ->add('tipoMovimiento', EntityType::class, ['class' => TipoMovimientoConcepto::class, 
                                                                  'required' => true,
                                                                  'placeholder' => 'Selecciones un movimiento...',
                                                                  'constraints' => [new NotNull(array('message' => "Debe seleccionar un movimiento!!"))]
                                                             ])
                        ->add('conMovProc', EntityType::class, [
                                                    'class'       => ConceptoMovimientoProceso::class,
                                                    
                                                  ])
                        ->add('artProcFaena', 
                                                  EntityType::class, 
                                                  [
                                                  'class' => ArticuloAtributoConcepto::class,                                           
                                                    ])
                        ->add('proceso', HiddenType::class, [
                                                              'data' => $proceso->getId()
                                                          ])
                        ->add('guardar', SubmitType::class, ['label' => 'Siguiente >>']);
                        if (count($proceso->getProcesoFaena()->getAutomaticos()))
                        {
                          $form->add('automatic', SubmitType::class, ['label' => 'Generar Movimientos Automaticos >>']);
                        }
                        $form->setAction($this->generateUrl('bd_adm_proc_fan_day_procesar', ['proc' => $proceso->getId(), 'fd' => $fd]));
                        
        return $form->getForm();
        //'constraints' => [new NotNull(array('message' => "Debe seleccionar un concepto!!"))]
        //'constraints' => [new NotNull(array('message' => "Debe seleccionar un articulo!!"))]
    }

    /**
     * @Route("/chgtm", name="bd_change_tipo_movimiento", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function changeTipoMovimiento(Request $request)
    {
      $idTipo = $request->request->get("tipoMovimiento");
      $idProceso = $request->request->get("proceso");
      $em = $this->getDoctrine()->getManager();
      $tipoMovimiento = $em->find(TipoMovimientoConcepto::class, $idTipo);
      $procesoFaenaDiaria = $em->find(ProcesoFaenaDiaria::class, $idProceso);
      $repository = $em->getRepository(ConceptoMovimientoProceso::class);
      try {
            $conceptos = $repository->findAllConceptosByTipo($procesoFaenaDiaria->getProcesoFaena(), $tipoMovimiento);
      } catch (\Exception $e) {
        return new JsonResponse(array('ok' => $e->getMessage()));
      }
      
      return new JsonResponse($conceptos);
    }

    /**
     * @Route("/chgcon", name="bd_change_concepto_movimiento", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function changeConceptoMovimiento(Request $request)
    {
      $idConcepto = $request->request->get("concepto");
      $em = $this->getDoctrine()->getManager();
      $concepto = $em->find(ConceptoMovimientoProceso::class, $idConcepto);
      $articulos = array();
      foreach ($concepto->getArticulos() as $art) {
        if ($art->getActivo())
          $articulos[] = array('id' => $art->getId(), 'show' => $art."");
      }
      return new JsonResponse($articulos);
    }

    /**
     * @Route("/genauto/{proc}/{fan}", name="bd_generate_movimientos_automaticos")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function generateMovimientosAutmaticos($proc, $fan)
    {
      $em = $this->getDoctrine()->getManager();
      $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
      $faena = $em->find(FaenaDiaria::class, $fan);
      $procFaena = $proceso->getProcesoFaena();

      $automaticos = $procFaena->getAutomaticos();

      if ($automaticos)
      {
          $automaticos = $procFaena->getAutomaticos()->getIterator();
          $automaticos->uasort(
                                  function ($p1, $p2)
                                  {
                                          if ($p1->getConcepto()->getTipoMovimiento()->getInstancia() < $p2->getConcepto()->getTipoMovimiento()->getInstancia() ) {
                                              return -1;
                                          }
                                          else {
                                              return 1;
                                          }          
                                  }
                              );
      }

      foreach ($automaticos as $auto) 
      {
       //   throw new \Exception("Error Processing Request ".$auto->getConcepto()->getTipoMovimiento()->getInstancia(), 1);
          
          $instance = $auto->getConcepto()->getTipoMovimiento()->getInstancia();
          if ($instance == 4)
          {
            if (!$proceso)
              throw new \Exception("Proceso inexistente", 1);
            $this->procesarTransformarStock($proceso, $auto, $auto->getConcepto(), $faena, $em);
          }
          elseif($instance == 5)
          {
            $var = (bool)true;
            $this->procesarTransferirStock($proceso, $auto, $auto->getConcepto(), $faena, $em, null, $var);
          }
          elseif (in_array($instance, [2,3])) 
          {
           return $this->procesarEntradaSalidaStock($proceso, $auto, $auto->getConcepto(), $faena, $instance, $em);
          }
      }
      $em->flush();
      return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fan]);
    }

    /**
     * @Route("/gstProcFanDay/{proc}/{fd}", name="bd_adm_proc_fan_day")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function administrarProcesoFaenaDiaria($proc, $fd)
    {   
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);
        if ($proceso->getProcesoFaena()->getInstance() == 3)
        {
           return $this->getFormProccessMedium($proceso, $faena);
        }
        $repository = $em->getRepository('GestionFaenaBundle:faena\MovimientoStock');
        $movimientos = $repository->findAllMovimientos($proceso);
        $conceptos = array();
        $movStock = array();
        $datos = array();
        $formsDelete = array();
        $totales = array();
        $concMovimientos = array();
        foreach ($movimientos as $mov)
        {
            $idTrx = ($mov->getOrigen()?$mov->getOrigen()->getId():($mov->getDestino()?$mov->getDestino()->getId():0));  
            $formsDelete[$mov->getId()] = $this->getFormDeleteMovimiento($mov->getId(), $idTrx, $fd)->createView();
            $movStock[] = $mov->getId();
            $keyMov = array_search($mov->getId(), $movStock);
            $datos[$keyMov] = array();
            $concMovimientos[$keyMov] = array('con'=> $mov->getArtProcFaena()->getConcepto(), 'art' => $mov->getArtProcFaena(), 'mov' => $mov);
            foreach ($mov->getValores() as $valor) { 
              
              if (($valor->getAtributo()?$valor->getAtributo()->getMostrar():$valor->getMostrar()))
              {        
                  if (!in_array(($valor->getAtributo()?$valor->getAtributo()->getAtributoAbstracto():$valor->getAtributoAbstracto
                    ()), $conceptos)){
                    $conceptos[] = ($valor->getAtributo()?$valor->getAtributo()->getAtributoAbstracto():$valor->getAtributoAbstracto());
                  }
                  $keyConcepto = array_search($valor->getAtributo()?$valor->getAtributo()->getAtributoAbstracto():$valor->getAtributoAbstracto(), $conceptos);
                  $datos[$keyMov][$keyConcepto] = array('data' => $valor->getData(), 'mov' => $mov->getId(), 'art' => $mov->getArtProcFaena()->getId(), 'proc' => $mov->getProcesoFnDay()->getId(), 'trx' => $idTrx);
                  if (($valor->getAtributo()?$valor->getAtributo()->getAcumula():$valor->getAcumula()))
                  {
                    if (!isset($totales[$keyConcepto]))
                    {
                        $totales[$keyConcepto] = array('cant' => 0, 'total' => 0);
                    }
                    if (($valor->getAtributo()?$valor->getAtributo()->getPromedia():$valor->getPromedia()))
                      $totales[$keyConcepto]['cant']++;
                    else
                      $totales[$keyConcepto]['cant'] = 1;
                    $totales[$keyConcepto]['total']+= $valor->getData(false);
                  }
              }
            }
        }
        $form = $this->getFormBeginMovStockAction($proceso, $fd);
        return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('con' => $concMovimientos, 'totales' =>$totales,'formsDelete' => $formsDelete, 'movs' => $movStock, 'conceptos' => $conceptos, 'datos' => $datos, 'movimientos' => $movimientos, 'proceso' => $proceso, 'form' => $form->createView(), 'faena' => $faena));
    }

    /**
     * @Route("/gstProcFanDayProc/{proc}/{fd}", name="bd_adm_proc_fan_day_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function administrarProcesoFaenaDiariaProcesar(Request $request, $proc, $fd)
    {
          $em = $this->getDoctrine()->getManager();
          $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
          $faena = $em->find(FaenaDiaria::class, $fd);
          $form = $this->getFormBeginMovStockAction($proceso, $fd);
          $form->handleRequest($request);
          $data = $form->getData();
          $errors = array();
          if (!$form->get('guardar')->isClicked())
          {
            $form = $this->createFormBuilder()
                         ->add('generar', SubmitType::class, ['label' => 'Generar movimientos'])
                         ->setAction($this->generateUrl('bd_generate_movimientos_automaticos', 
                                                        ['proc' => $proceso->getId(), 'fan' => $faena->getId()]))
                         ->getForm();     
            return $this->render('@GestionFaena/faena/generarMovimientosAutomaticos.html.twig', 
                                ['form' => $form->createView(), 'faena' => $faena, 'proceso' => $proceso]);
          }
          else
          {
            if ($form->isValid())
            {
              $data = $form->getData();

              $tipoMovimiento = $data['tipoMovimiento'];

              $movimiento = $tipoMovimiento->getInstanciaMovimiento();
              $movimiento->setProcesoFnDay($proceso);
              $movimiento->setFaenaDiaria($faena);
              $movimiento->setArtProcFaena($data['artProcFaena']);
              //$movimiento->setConcepto($data['conMovProc']);
              $validator = $this->get('validator');
              $errors = $validator->validate($movimiento);
              if (count($errors) == 0) 
              {
                  $errorsString = (string) $errors;
                  $movimiento->generateAtributes();
                  $formAtr = $this->getFormAddMovStock($movimiento, $proceso, $data['artProcFaena'], 'bd_adm_proc_mov_st', $faena);
                  return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'form' => $form->createView(), 'faena' => $faena));
              }
            }
          }

          $repository = $em->getRepository('GestionFaenaBundle:faena\MovimientoStock');
          $movimientos = $repository->findAllMovimientos($proceso);
          $conceptos = array();
          $movStock = array();
          $datos = array();
          $formsDelete = array();
          $totales = array();
          $concMovimientos = array();
          foreach ($movimientos as $mov)
          {
              $idTrx = ($mov->getOrigen()?$mov->getOrigen()->getId():($mov->getDestino()?$mov->getDestino()->getId():0));  
              $formsDelete[$mov->getId()] = $this->getFormDeleteMovimiento($mov->getId(), $idTrx, $fd)->createView();
              $movStock[] = $mov->getId();
              $keyMov = array_search($mov->getId(), $movStock);
              $datos[$keyMov] = array();
              $concMovimientos[$keyMov] = array('con'=> $mov->getArtProcFaena()->getConcepto(), 'art' => $mov->getArtProcFaena(), 'mov' => $mov);
              foreach ($mov->getValores() as $valor) { 
                
                if (($valor->getAtributo()?$valor->getAtributo()->getMostrar():$valor->getMostrar()))
                {        
                    if (!in_array(($valor->getAtributo()?$valor->getAtributo()->getAtributoAbstracto():$valor->getAtributoAbstracto
                      ()), $conceptos)){
                      $conceptos[] = ($valor->getAtributo()?$valor->getAtributo()->getAtributoAbstracto():$valor->getAtributoAbstracto());
                    }
                    $keyConcepto = array_search($valor->getAtributo()?$valor->getAtributo()->getAtributoAbstracto():$valor->getAtributoAbstracto(), $conceptos);
                    $datos[$keyMov][$keyConcepto] = array('data' => $valor->getData(), 'mov' => $mov->getId(), 'art' => $mov->getArtProcFaena()->getId(), 'proc' => $mov->getProcesoFnDay()->getId(), 'trx' => $idTrx);
                    if (($valor->getAtributo()?$valor->getAtributo()->getAcumula():$valor->getAcumula()))
                    {
                      if (!isset($totales[$keyConcepto]))
                      {
                          $totales[$keyConcepto] = array('cant' => 0, 'total' => 0);
                      }
                      if (($valor->getAtributo()?$valor->getAtributo()->getPromedia():$valor->getPromedia()))
                        $totales[$keyConcepto]['cant']++;
                      else
                        $totales[$keyConcepto]['cant'] = 1;
                      $totales[$keyConcepto]['total']+= $valor->getData(false);
                    }
                }
              }
          }
          return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('errors' => $errors, 'con' => $concMovimientos, 'totales' =>$totales,'formsDelete' => $formsDelete, 'movs' => $movStock, 'conceptos' => $conceptos, 'datos' => $datos, 'movimientos' => $movimientos, 'proceso' => $proceso, 'form' => $form->createView(), 'faena' => $faena));
    }

    private function getFormProccessMedium(ProcesoFaenaDiaria $proceso, FaenaDiaria $faena)
    {
        $form = $this->getFormBeginMovStockAction($proceso, $faena->getId());
        $detalle = $this->getFormTipoMovimienos($proceso, $faena);
        return $this->render('@GestionFaena/faena/adminProcFanDayMedium.html.twig', 
                             ['form' => $form->createView(), 
                              'proceso' => $proceso,
                              'faena' => $faena,
                              'formDetalle' => $detalle->createView()
                             ]);
    }

    /**
     * @Route("/gstmovproc/{proc}/{fd}", name="bd_adm_get_movimientos_proceso", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormMovimientosProceso(Request $request, $proc, $fd)
    {   
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);
        $form = $this->getFormTipoMovimienos($proceso, $faena);
        $form->handleRequest($request);
        $data = $form->getData();
        $repo = $em->getRepository(MovimientoStock::class);
        $articulo = $data['articulos'];   
        $formsDelete = [];
        if ($data['tipoMovimiento'] == 'M') //selecciono la opcion de mostrar los movimientos
        {                         
              $movimientos = $repo->findAllMovimientos($proceso);
              $headers = ['tipo' => ['data' => 'Tipo Movimiento', 'numeric' => false], 
                          'numero' => ['data' => 'Numero', 'numeric' => false], 
                          'conc' => ['data'=> 'Concepto', 'numeric' => false], 
                          'art' => ['data' => 'Articulo', 'numeric' => false]
                         ];
              $body = [];
              $i = 0;
              $totales = ['tipo' => 'TOTALES', 'conc' => '', 'art' => '', 'numero' => ''];
              $informaTotales = false;
              if ($articulo)
              {
                $informaTotales = true;
              }
              
              foreach ($movimientos as $mov) 
              {
                  $computar = true;
                  $acumular = false;
                  if ($articulo)
                  {
                      if ($mov->getArtProcFaena()->getArticulo() != $articulo)
                      {
                          $computar = false;
                      }
                      else
                      {
                          $acumular = true;
                      }
                  }

                  if ($computar)
                  {
                    $idTrx = ($mov->getOrigen()?$mov->getOrigen()->getId():($mov->getDestino()?$mov->getDestino()->getId():0));  
                    $formsDelete[$mov->getId()] = $this->getFormDeleteMovimiento($mov->getId(), $idTrx, $fd)->createView();

                    $body[$i] = ['tipo' => $mov, 'conc' => $mov->getArtProcFaena()->getConcepto()->getConcepto()."", 'numero' => $mov->getId()];
                    $body[$i]['id'] = $mov->getId();
                    $body[$i]['trx'] = $idTrx;
                    foreach ($mov->getValores() as $valor) 
                    {
                        $atributo = ($valor->getAtributoAbstracto()?$valor->getAtributoAbstracto():$valor->getAtributo()->getAtributoAbstracto());
                        if ($valor->getMostrar())
                        {                            
                            $headers[$atributo->getId()] = ['data' => $atributo, 'numeric' => $valor->isNumeric()];
                        }
                        $body[$i][$atributo->getId()] = $valor->getData()."";
                        $body[$i]['art'] = $mov->getArtProcFaena()->getArticulo();
                        if ($acumular)
                        {
                            if (!array_key_exists($atributo->getId(), $totales))
                                $totales[$atributo->getId()] = 0;
                            $totales[$atributo->getId()]+= $valor->getData();
                        }
                        
                    }
                    $i++;
                  }
              }
        }
        elseif ($data['tipoMovimiento'] == 'E')
        {
            $headers = ['tipo' => ['data' => 'Detalle', 'numeric' => false], 
                        'art' => ['data' => 'Articulo', 'numeric' => false]
                       ];
            $body = [];
            $movimientos = $repo->findAllMovimientos($proceso);
            $informaTotales = false;
            
            foreach ($movimientos as $mov) 
            {
                $art = $mov->getArtProcFaena()->getArticulo();
                if ((!$articulo) || ($articulo == $art))
                {
                    if (!array_key_exists($art->getId(), $body))
                    {
                        $body[$art->getId()] = ['tipo' => 'Existencias totales', 'art' => $art, 'trx' => 0];
                    }
                    foreach ($mov->getValores() as $valor) 
                    {
                        if ($valor->isNumeric())
                        {
                          $atributo = ($valor->getAtributoAbstracto()?$valor->getAtributoAbstracto():$valor->getAtributo()->getAtributoAbstracto());
                          if ($valor->getMostrar())
                          {      
                            $headers[$atributo->getId()] = ['data' => $atributo, 'numeric' => true];
                          }
                          if (!array_key_exists($atributo->getId(), $body[$art->getId()]))
                          {
                                $body[$art->getId()][$atributo->getId()] = 0;
                          }
                          $body[$art->getId()][$atributo->getId()]+= $valor->getData();
                        }
                        
                    }
                }
            }
        }

        $formBegin = $this->getFormBeginMovStockAction($proceso, $fd);
        $params = [ 'form' => $formBegin->createView(), 
                    'proceso' => $proceso,
                    'faena' => $faena,
                    'formDetalle' => $form->createView(),
                    'body' => $body,
                    'headers' => $headers
                   ];
        if ($informaTotales)
        {
            $params['totales'] = $totales;
        }
        if (count($formsDelete))
        {
          $params['formsDelete'] = $formsDelete;
        }
        return $this->render('@GestionFaena/faena/adminProcFanDayMedium.html.twig', $params);
    }


    private function getFormTipoMovimienos($proceso, $faena)
    {
          $form = $this->createFormBuilder()
                        ->add('articulos', 
                              EntityType::class, 
                              [
                                'class' => Articulo::class,
                                'query_builder' => function (EntityRepository $er) use ($proceso){
                                                                                          return $er->createQueryBuilder('a')
                                                                                                    ->join('a.artsAtrConc', 'aac')
                                                                                                    ->join('aac.concepto', 'cmp')
                                                                                                    ->join('cmp.procesoFaena', 'pf')
                                                                                                    ->join('pf.procesosFaenaDiaria', 'pfd')
                                                                                                    ->join('aac.atributos', 'atr')
                                                                                                    ->join('atr.valoresAtributos', 'valatr')
                                                                                                    ->where('pfd = :proceso')
                                                                                                    ->setParameter('proceso', $proceso)
                                                                                                    ->groupBy('a, atr')
                                                                                                    ->orderBy('a.nombre', 'ASC');
                                                                                                  },
                                'empty_data' => null,
                                'required' => false,
                                'placeholder' => 'Todos',
                              ])
                          ->add('tipoMovimiento', 
                              ChoiceType::class, 
                              [
                                'choices' => ['Existencias' => 'E', 'Movimientos' => 'M']
                              ])
                          ->add('load', SubmitType::class, ['label' => 'Cargar'])   
                          ->setAction($this->generateUrl('bd_adm_get_movimientos_proceso', ['proc' => $proceso->getId(), 'fd' => $faena->getId()]))
                          ->setMethod('POST')
                          ->getForm();
          return $form;
    }

    private function procesarTransformarStock(ProcesoFaenaDiaria $proceso,
                                              ArticuloAtributoConcepto $articulo,
                                              ConceptoMovimientoProceso $concepto,
                                              FaenaDiaria $faena,
                                              $em)
    {
              $stock = 0;
              $movimiento = new TransformarStock(); 
              $movimiento->setArtProcFaena($articulo);
              $movimiento->setProcesoFnDay($proceso);
              $movimiento->setFaenaDiaria($faena);
              $movimiento->generateAtributes();
              $formAtr = $this->getFormAddMovStock($movimiento, $proceso, $articulo, 'bd_adm_proc_mov_st', $faena);
             // $formAtr->handleRequest($request);
              $movimiento->updateValues($stock, $em);


              ///////////Debe quitar del stock el articulo base   (Ejemplo: Aves -> Corazon)
              ///Verifica si el Articulo base del proceso se encuentra configurado para manejar el stock
              $articuloBase = $concepto->getArticuloOrigenTransformacion();
              $articuloBaseManejaStock = $proceso->getProcesoFaena()->existeArticuloDefinidoManejoStock($articuloBase); //articulo base 

              $articuloDestino = $articulo->getArticulo();
              $articuloDestinoManejaStock = $proceso->getProcesoFaena()->existeArticuloDefinidoManejoStock($articuloDestino); //articulo destino 

              if (!$articuloBaseManejaStock) //no esta configurado el articulo base para manejar el stock en el proceso
              {
                $this->addFlash('error', "El articulo ".$articuloBase." no se encuentra definido para manejar stock");
                return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', ['fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'faena' => $faena]);
              }

              if (!$articuloDestinoManejaStock) //no esta configurado el articulo destino para manejar el stock en el proceso
              {
                $this->addFlash('error', "El articulo ".$articuloDestino." no se encuentra definido para manejar stock");
                return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', ['fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'faena' => $faena]);
              }

              //1º: Recupera del proceso, el ArticuloAtributoConcepto para el TipoMovimiento-> Salida Stock
              $artAtrConSalida = $this->getArticuloAtributoConceptoForMovimiento($articuloBase,
                                                                                  $articulo->getConcepto()->getConcepto(),
                                                                                  SalidaStock::getInstance(),
                                                                                  $proceso->getProcesoFaena(),
                                                                                  $em);

              //Recupera la cantidad del Articulo (No del articulo base....para el ejemplo Recupera la cantidad de Corazon que sera la que debera descontar de las Aves, este valor es el mismo tanto para la entrada como para la salida, 
              //RECORDAR CONFIGURAR L ARTICULO BASE!!
              $valorAtributo = $movimiento->getValorWhitAtribute($articuloDestinoManejaStock->getAtributo());


              ///Ya puede generar la salida del stcok del articulo base
              $salida = new SalidaStock();
              $salida->setFaenaDiaria($faena);
              //$salida->addValore($valorAtr);
              $salida->setProcesoFnDay($proceso);
              $salida->setArtProcFaena($artAtrConSalida);

            /*  foreach ($movimiento->getValores() as $valor) {

                 // $valorAtributo = $movimiento->getValorWhitAtribute($articuloManejaStock->getAtributo());
                  $valorAtr = new ValorNumerico();
                  $valorAtr->setAtributoAbstracto($valor->getAtributo()->getAtributoAbstracto());
                  $dataValue = $valor->getValor();
                  $valorAtr->setValor($dataValue);
                  $valorAtr->setUnidadMedida($valor->getUnidadMedida());
                  $valorAtr->setMostrar($valor->getAtributo()->getMostrar());
                  $valorAtr->setDecimales($valor->getAtributo()->getDecimales());
                  $valorAtr->setAcumula(true);
                  $salida->addValore($valorAtr);
              }*/

              $valorAtr = new ValorNumerico();
              $valorAtr->setAtributoAbstracto($valorAtributo->getAtributo()->getAtributoAbstracto());
              $valorAtr->setValor($valorAtributo->getValor());
              $valorAtr->setUnidadMedida($valorAtributo->getUnidadMedida());
              $valorAtr->setMostrar($valorAtributo->getAtributo()->getMostrar());
              $valorAtr->setDecimales($valorAtributo->getAtributo()->getDecimales());
              $valorAtr->setAcumula(true);
              $salida->addValore($valorAtr);
              $em->persist($salida);

              ///Una vez realizada la salida delstock del articulo base, debe proceder a realizar la entrada del articulo destino (Corazon)
              //1º: Recupera del proceso, el ArticuloAtributoConcepto para el TipoMovimiento-> Entrada Stock
              $artAtrConEntrada = $this->getArticuloAtributoConceptoForMovimiento($articuloDestino,
                                                                                  $articulo->getConcepto()->getConcepto(),
                                                                                  EntradaStock::getInstance(),
                                                                                  $proceso->getProcesoFaena(),
                                                                                  $em);


             /* $valorAtr = new ValorNumerico();
              $valorAtr->setAtributoAbstracto($valorAtributo->getAtributo()->getAtributoAbstracto());
              $valorAtr->setValor($valorAtributo->getValor());
              $valorAtr->setUnidadMedida($valorAtributo->getUnidadMedida());
              $valorAtr->setMostrar($valorAtributo->getAtributo()->getMostrar());
              $valorAtr->setDecimales($valorAtributo->getAtributo()->getDecimales());
              $valorAtr->setAcumula(true);*/

              $entrada = new EntradaStock();
              $entrada->setFaenaDiaria($faena);
           //   $entrada->addValore($valorAtr);
              $entrada->setProcesoFnDay($proceso);
              $entrada->setArtProcFaena($artAtrConEntrada);
              foreach ($movimiento->getValores() as $valor) {

                 // $valorAtributo = $movimiento->getValorWhitAtribute($articuloManejaStock->getAtributo());
                  $valorAtr = new ValorNumerico();
                  $valorAtr->setAtributoAbstracto($valor->getAtributo()->getAtributoAbstracto());
                  $dataValue = $valor->getValor();
                  $valorAtr->setValor($dataValue);
                  $valorAtr->setUnidadMedida($valor->getUnidadMedida());
                  $valorAtr->setMostrar($valor->getAtributo()->getMostrar());
                  $valorAtr->setDecimales($valor->getAtributo()->getDecimales());
                  $valorAtr->setAcumula(true);
                  $entrada->addValore($valorAtr);
              }

              $em->persist($entrada);
              

              $movimiento->setMovimientoDestino($entrada);
              $movimiento->setMovimientoOrigen($salida);
              $em->persist($movimiento);
    }



    private function procesarEntradaSalidaStock(ProcesoFaenaDiaria $proceso,
                                                ArticuloAtributoConcepto $articulo,
                                                ConceptoMovimientoProceso $concepto,
                                                FaenaDiaria $faena,
                                                $type,
                                                $em, 
                                                Request $request = null)
    {
        if ($type == 2)
        {
              $movimiento = new EntradaStock();   
              $stock = 0;     
        }
        elseif($type == 3)
        {
              $stock = 0;
              $movimiento = new SalidaStock(); 
        }

        $movimiento->setArtProcFaena($articulo);
        $movimiento->setFaenaDiaria($faena);
        $movimiento->setProcesoFnDay($proceso);
        $movimiento->generateAtributes();
        $formAtr = $this->getFormAddMovStock($movimiento, $proceso, $articulo, 'bd_adm_proc_mov_st', $faena);
        if ($request)
        {
          $formAtr->handleRequest($request);
        }
        $movimiento->updateValues($stock, $em);

        $valid = $movimiento->verificarValores();
        if (!$valid['ok'])
        {
            $this->addFlash(
                                'errorLoad',
                                $valid['messages']
                            );
            return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', 
                                array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'faena' => $faena));                    
        }
        $em->persist($movimiento);
        $proceso->addMovimiento($movimiento);
        return $proceso;
    }

    private function procesarTransferirStock(ProcesoFaenaDiaria $proceso,
                                              ArticuloAtributoConcepto $articulo,
                                              ConceptoMovimientoProceso $concepto,
                                              FaenaDiaria $faena,
                                              $em,
                                              Request $request = null,
                                              $automatico = false)
    {
        $repository = $em->getRepository(TipoMovimientoConcepto::class);
        $stock = 0;
        $movimiento = new TransferirStock(); 
        $movimiento->setArtProcFaena($articulo);

        $movimiento->setProcesoFnDay($proceso);
        $movimiento->setFaenaDiaria($faena);
        $movimiento->generateAtributes();
        $formAtr = $this->getFormAddMovStock($movimiento, $proceso, $articulo, 'bd_adm_proc_mov_st', $faena);
      //  $formAtr->handleRequest($request);
        if ($request)
        {
          $formAtr->handleRequest($request);
        }

        $movimiento->updateValues($stock, $em, $automatico);

        $procesoDestino = $formAtr['destino']->getData();

        if (!$procesoDestino)
        {
           if ($automatico == true) //debe estar configurado el destino por defecto en el proceso correspondiente
           {
              if ($proceso->getProcesoFaena()->getProcesosDestinoDefault())
              {
                  $procesoDestino = $faena->getProceso($proceso->getProcesoFaena()->getProcesosDestinoDefault()->getId());
              }
           }
        }

        if ($procesoDestino)
        {
            /////////////////////////////ENTRADA DE STOCK A PROCESO DESTINO///////////////////////////////////////
                //El proceso destino existe, debe verificar que se encuentre definido el articulo de la transferencia como habilitado para manejar stock
                $articuloManejaStock = $procesoDestino->getProcesoFaena()->existeArticuloDefinidoManejoStock($articulo->getArticulo());
                if (!$articuloManejaStock) //no esta configurado el articulo para manejar el stock
                {
                  $this->addFlash(
                                      'error',
                                      "El articulo ".$articulo->getArticulo()." no se encuentra definido en el proceso ".$procesoDestino." para manejar stock"
                                  );
                  return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', 
                                      ['fatr' => $formAtr->createView(), 
                                       'movimiento' => $movimiento, 
                                       'proceso' => $proceso, 
                                       'faena' => $faena]);
                  // throw new \Exception("El articulo ".$articulo->getArticulo()." no se encuentra definido en el proceso ".$procesoDestino." para manejar stock");
                }
                //Busca en la FaenaDiaria correspondiente el ProcesoFaenaDiaria correspondiente al ProcesoFanea
                $procFanDay = $faena->getProceso($procesoDestino->getProcesoFaena()->getId());
                if (!$procFanDay) //no esta configurado el articulo para manejar el stock
                {
                    throw new \Exception("El proceso de destino es inexistente!!!");
                }
                //1º: Recupera del proceso destino, el ArticuloAtributoConcepto para el TipoMovimiento-> Entrada Stock
                $artAtrConDestino = $this->getArticuloAtributoConceptoForMovimiento($articulo->getArticulo(),
                                                                                    $articulo->getConcepto()->getConcepto(),
                                                                                    EntradaStock::getInstance(),
                                                                                    $procesoDestino->getProcesoFaena(),
                                                                                    $em);

                //busca en la lista de valores de atributos del movimiento si existe el valor correspondiente al AtributoAbstracto que maneja el stock del proceso
                $valorAtributo = $movimiento->getValorWhitAtribute($articuloManejaStock->getAtributo());
                if (!$valorAtributo)
                {
                  throw new \Exception("No se encuentra el atributo en la lista de valores del movimiento!!");
                }
                

                $tipoMovimiento = $repository->getTipoWithInstance(EntradaStock::getInstance());
                $procFaena = $procFanDay->getProcesoFaena();

                $ajuste = $procFaena->getAjusteAAplicar($tipoMovimiento, 
                                                      $artAtrConDestino->getConcepto()->getConcepto(),
                                                      $artAtrConDestino->getArticulo()
                                                      );

                $entrada = new EntradaStock();
                $entrada->setFaenaDiaria($faena);
               // $entrada->addValore($valorAtr);
                $entrada->setProcesoFnDay($procFanDay);
                $entrada->setArtProcFaena($artAtrConDestino);

                ///recorre la lista de atributos del movimiento Transferencia de Stock para aplicarlos a la salida
                foreach ($movimiento->getValores() as $valor) {
                    $valorAtr = new ValorNumerico();
                    $valorAtr->setAtributoAbstracto($valor->getAtributo()->getAtributoAbstracto());

                    $dataValue = $valor->getValor();

                    if ($articuloManejaStock->getAtributo() == $valor->getAtributo()->getAtributoAbstracto())
                    {
                      $valorAtr->setValor(($dataValue*$ajuste));
                    }
                    else
                    {
                      $valorAtr->setValor($dataValue);
                    }

                    $valorAtr->setUnidadMedida($valor->getUnidadMedida());
                    $valorAtr->setMostrar($valor->getAtributo()->getMostrar());
                    $valorAtr->setDecimales($valor->getAtributo()->getDecimales());
                    $valorAtr->setAcumula(true);
                    $entrada->addValore($valorAtr);
                }
                $em->persist($entrada);
                $proceso->addMovimiento($entrada);
            /////////////////////////FIN GENERACION ENTRADA A PROCESO DESTINO//////////////////////////////////////////

            /////////////////////////////SALIDA DE STOCK DE PROCESO ORIGEN///////////////////////////////////////
                //El proceso destino existe, debe verificar que se encuentre definido el articulo de la transferencia como habilitado para manejar stock
                $procesoOrigen = $proceso->getProcesoFaena();
                $articuloManejaStock = $procesoOrigen->existeArticuloDefinidoManejoStock($articulo->getArticulo());
                if (!$articuloManejaStock) //no esta configurado el articulo para manejar el stock
                {
                   throw new \Exception("El articulo ".$articulo->getArticulo()." no se encuentra definido en el proceso <b>".$procesoOrigen."</b> para manejar stock");
                }

                //1º: Recupera del proceso origen, el ArticuloAtributoConcepto para el TipoMovimiento-> Salida Stock
                $artAtrConOrigen = $this->getArticuloAtributoConceptoForMovimiento($articulo->getArticulo(),
                                                                                    $articulo->getConcepto()->getConcepto(),
                                                                                    SalidaStock::getInstance(),
                                                                                    $procesoOrigen,
                                                                                    $em);
                //busca en la lista de valores de atributos del movimiento si existe el valor correspondiente al AtributoAbstracto que maneja el stock del proceso
                $valorAtributo = $movimiento->getValorWhitAtribute($articuloManejaStock->getAtributo());
                if (!$valorAtributo)
                {
                  throw new \Exception("No se encuentra el atributo en la lista de valores del movimiento!!");
                }

                $repositoryMovimiento = $em->getRepository(MovimientoStock::class);
                $stockArticulo = $proceso->getStockArticulo($faena, $articulo->getArticulo(), $articuloManejaStock->getAtributo());

                //$repositoryMovimiento->getStockDeArticulo($proceso, $articuloManejaStock->getArticulo(), $articuloManejaStock->getAtributo());
                if (!$stockArticulo)
                {
                  $this->addFlash(
                                      'error',
                                      "No se pudo calcular el stock del articulo ".$articuloManejaStock->getArticulo().", en el proceso ".$proceso."!!"
                                  );
                  return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', 
                                      ['fatr' => $formAtr->createView(), 
                                       'movimiento' => $movimiento, 
                                       'proceso' => $proceso, 
                                       'faena' => $faena]);

                }
                elseif($stockArticulo < $valorAtributo->getValor())
                {
                  throw new \Exception("El stock del articulo ".$articuloManejaStock->getArticulo()." es insuficiente!!");
                  $this->addFlash(
                                      'error',
                                      "El stock del articulo ".$articuloManejaStock->getArticulo()." es insuficiente!!"
                                  );
                  return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', 
                                      ['fatr' => $formAtr->createView(), 
                                       'movimiento' => $movimiento, 
                                       'proceso' => $proceso, 
                                       'faena' => $faena]);
                }
                elseif($valorAtributo->getValor() == 0)
                {
                  //throw new \Exception("El stock del articulo ".$articuloManejaStock->getArticulo()." es insuficiente!!");
                  $this->addFlash(
                                      'error',
                                      "La cantidad a transferir debe ser mayor a 0!!"
                                  );
                  return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', 
                                      ['fatr' => $formAtr->createView(), 
                                       'movimiento' => $movimiento, 
                                       'proceso' => $proceso, 
                                       'faena' => $faena]);
                }

             //   $valorAtr = new ValorNumerico();
             //   $valorAtr->setAtributoAbstracto($valorAtributo->getAtributo()->getAtributoAbstracto());
             //   $valorAtr->setValor($valorAtributo->getValor());
              //  $valorAtr->setUnidadMedida($valorAtributo->getUnidadMedida());
              //  $valorAtr->setMostrar($valorAtributo->getAtributo()->getMostrar());
              //  $valorAtr->setDecimales($valorAtributo->getAtributo()->getDecimales());
             //   $valorAtr->setAcumula(true);
                $salida = new SalidaStock();
                $salida->setFaenaDiaria($faena);
            //   $salida->addValore($valorAtr);
                $salida->setProcesoFnDay($proceso);
                $salida->setArtProcFaena($artAtrConOrigen);  
                foreach ($movimiento->getValores() as $valor) {
                    $valorAtr = new ValorNumerico();
                    $valorAtr->setAtributoAbstracto($valor->getAtributo()->getAtributoAbstracto());

                    $dataValue = $valor->getValor();

                  //  if ($articuloManejaStock->getAtributo() == $valor->getAtributo()->getAtributoAbstracto())
                  //  {
                   //   $valorAtr->setValor(($dataValue*$ajuste));
                  //  }
                 //   else
                 //   {
                      $valorAtr->setValor($dataValue);
                //    }
                    $valorAtr->setUnidadMedida($valor->getUnidadMedida());
                    $valorAtr->setMostrar($valor->getAtributo()->getMostrar());
                    $valorAtr->setDecimales($valor->getAtributo()->getDecimales());
                    $valorAtr->setAcumula($valor->getAtributo()->getAcumula());
                    $salida->addValore($valorAtr);
                }
              
                $em->persist($salida);
                $proceso->addMovimiento($salida);
            /////////////////////////FIN GENERACION SALIDA DE PROCESO ORIGEN//////////////////////////////////////////
        }
        else
        {
                  $this->addFlash(
                                      'error',
                                      "Debe seleccionar un proceso destino"
                                  );
                  return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', 
                                      ['fatr' => $formAtr->createView(), 
                                       'movimiento' => $movimiento, 
                                       'proceso' => $proceso, 
                                       'faena' => $faena]);
        }
        
        $ok = true;
        if ($request)
        {
            $ok = $formAtr->isValid();
        }

        if ($ok)
        {
              $movimiento->setMovimientoDestino($entrada);
              $movimiento->setMovimientoOrigen($salida);              
              $em->persist($movimiento);
              $proceso->addMovimiento($movimiento);
              $proceso->setUltimoMovimiento(new \DateTime());
              return $proceso;
        }
        else{
          return new Response('pedazo de japi '.$formAtr->getErrors());
        }
    }

    /**
     * @Route("/gstMovProc/{proc}/{art}/{conc}/{type}/{fanday}", name="bd_adm_proc_mov_st", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarMovimientoStockAction(Request $request, $proc, $art, $conc, $type, $fanday)
    {

        $em = $this->getDoctrine()->getManager();
       // $repository = $em->getRepository(TipoMovimientoConcepto::class);
        
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        $concepto = $em->find(ConceptoMovimientoProceso::class, $conc);
        $faena = $em->find(FaenaDiaria::class, $fanday);
        if($type == 4) //TransformarStock
        {
              $this->procesarTransformarStock($proceso, $articulo, $concepto, $faena, $em);
              $proceso->setUltimoMovimiento(new \DateTime());
              $em->flush();
        }
        elseif($type == 5)  //comienza proceso Transferencia de stockk
        {
              $this->procesarTransferirStock($proceso, $articulo, $concepto, $faena, $em, $request);
              $proceso->setUltimoMovimiento(new \DateTime());
              $em->flush();
        }

        if (in_array($type, [2, 3, '2', '3']))
        {
//          throw new \Exception("Error Processing Request ".$type, 1);
          $this->procesarEntradaSalidaStock($proceso, $articulo, $concepto, $faena, $type, $em, $request);
          $proceso->setUltimoMovimiento(new \DateTime());
          $em->flush();
          return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fanday]);
        }
        else
        {
          return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fanday]);
        }
      //  return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso));
        
    }

    private function getArticuloAtributoConceptoForMovimiento(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo,
                                                              \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto,
                                                              $instanceOfTipoMovimiento,
                                                              \GestionFaenaBundle\Entity\ProcesoFaena $proceso,
                                                              $em) 
    //para los parametro dados, devuelve un ArticuloAtributoConcepto si existe, sino crea uno
    {
              //busca si existe el articulo atributo concepto para 
              $repositoryAAC = $em->getRepository(ArticuloAtributoConcepto::class);
              $artAtrCon = $repositoryAAC->findArticuloAtributoConcepto($articulo, $concepto, $instanceOfTipoMovimiento, $proceso);
              
              if (!$artAtrCon) //no existe el articulo, debe generar uno nuevo
              {
                //debe buscar primero si ya existe el concepto para la transferencia
                $repositoryCMP = $em->getRepository(ConceptoMovimientoProceso::class);
                $conMovProc = $repositoryCMP->getConceptoMovimientoProceso($proceso, $concepto, $instanceOfTipoMovimiento);
                if (!$conMovProc) //no existe el concepto del movimiento
                {
                    $repositoryTM = $em->getRepository(TipoMovimientoConcepto::class);
                    $tipoMovimiento = $repositoryTM->getTipoWithInstance($instanceOfTipoMovimiento);
                    if (!$tipoMovimiento){
                      throw new \Exception("No se ha definido el movimiento de Salida de Stock");
                      
                    }
                    $conMovProc = new ConceptoMovimientoProceso();
                    $conMovProc->setProcesoFaena($proceso);
                    $conMovProc->setConcepto($concepto); 
                    $conMovProc->setTipoMovimiento($tipoMovimiento);
                    $conMovProc->setAutomatico(true);
                    $em->persist($conMovProc);
                }

                $artAtrCon = new ArticuloAtributoConcepto();
                $artAtrCon->setConcepto($conMovProc);
                $artAtrCon->setArticulo($articulo);
                $em->persist($artAtrCon);
              }
              return $artAtrCon;
    }


    /**
     * @Route("/qdelmov/{mov}/{trx}", name="query_consulta_movimiento", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function queryMovimiento($mov, $trx, Request $request)  //dano un id busca el movimiento asociado
    {
      try{
          $entityManager = $this->getDoctrine()->getManager();
          $movimiento = $entityManager->find(MovimientoStock::class, $mov);
          $transferencia = $entityManager->find(MovimientoCompuesto::class, $trx);
          if ($transferencia->getFaenaDiaria()->getFinalizada())
          {
              return new JsonResponse(array('status' => false, 'msge' => 'La faena ya se encuentra finalizada!!'));
          }

          $movAsoc = ($transferencia->getMovimientoOrigen()->getId() == $movimiento->getId()?$transferencia->getMovimientoDestino():$transferencia->getMovimientoOrigen());
          $msge = "El movimiento ".strtoupper($transferencia->getMovimientoOrigen())." correspondiente al proceso ".strtoupper($transferencia->getMovimientoOrigen()->getProcesoFnDay())." tiene asociado el movimiento ".strtoupper($transferencia->getMovimientoDestino())." del proceso ".strtoupper($transferencia->getMovimientoDestino()->getProcesoFnDay()).", los dos movimientos seran eliminados. Continuar?";

          return new JsonResponse(array('msge' => $msge));
        }
        catch (\Exception $e){
                                return new JsonResponse(array('status' => false, 'msge' => $e->getMessage()));
        }
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

    private function getFormAddMovStock(MovimientoStock $movimiento, ProcesoFaenaDiaria $proc, ArticuloAtributoConcepto $art, $url, FaenaDiaria $fanday)
    {
        if ($movimiento->getType() == 2)
        return $this->createForm(EntradaStockType::class, 
                                 $movimiento, 
                                 ['action' => $this->generateUrl($url, 
                                                               ['type' => $movimiento->getType(), 
                                                                'proc' => $proc->getId(), 
                                                                'art' => $art->getId(), 
                                                                'conc' => $movimiento->getArtProcFaena()->getConcepto()->getId(),
                                                                'mov' => $movimiento->getId(),
                                                                'fanday' => $fanday->getId()]),
                                 'method' => 'POST']);
        elseif ($movimiento->getType() == 3){
                  return $this->createForm(SalidaStockType::class, 
                                 $movimiento, 
                                 ['action' => $this->generateUrl($url, 
                                                                ['type' => $movimiento->getType(), 
                                                                 'proc' => $proc->getId(), 
                                                                 'conc' => $movimiento->getArtProcFaena()->getConcepto()->getId(), 
                                                                 'art' => $art->getId(), 
                                                                 'mov' => $movimiento->getId(),
                                                                 'fanday' => $fanday->getId()]),
                                 'method' => 'POST']);
        }
        elseif ($movimiento->getType() == 4){
                  return $this->createForm(TransformarStockType::class, 
                                 $movimiento, 
                                 ['faena' => $fanday,
                                  'proceso' => $proc,
                                  'articulo' => $art->getConcepto()->getArticuloOrigenTransformacion(),
                                  'action' => $this->generateUrl($url, 
                                                                ['type' => $movimiento->getType(), 
                                                                 'proc' => $proc->getId(), 
                                                                 'conc' => $movimiento->getArtProcFaena()->getConcepto()->getId(), 
                                                                 'art' => $art->getId(), 
                                                                 'mov' => $movimiento->getId(),
                                                                 'fanday' => $fanday->getId()]),
                                 'method' => 'POST']);
        }
        elseif ($movimiento->getType() == 5){
                 // $repository = $this->getDoctrine()->getManager()->getRepository(MovimientoStock::class);
                 // $stock = //$repository->getStockArticulos($proc, $art->getArticulo(), $fanday);
                  //throw new \Exception("El stock es de ".$stock['nombre']." es de ".$stock['cantidad']);
                  return $this->createForm(TransferirStockType::class, 
                                           $movimiento, 
                                           ['faena' => $fanday,
                                            'proceso' => $proc,
                                            'articulo' => $art->getArticulo(),
                                            'action' => $this->generateUrl($url, 
                                                                          ['type' => $movimiento->getType(), 
                                                                           'proc' => $proc->getId(), 
                                                                           'conc' => $movimiento->getArtProcFaena()->getConcepto()->getId(), 
                                                                           'art' => $art->getId(), 
                                                                           'mov' => $movimiento->getId(),
                                                                           'fanday' => $fanday->getId()]),
                                           'method' => 'POST']);
        }
    }



    /**
     * @Route("/gstMovEdit/{mov}/{proc}/{art}/{fanday}", name="bd_adm_mov_st_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarMovimientoStockAction($mov, $proc, $art, $fanday)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $movimiento = $em->find(MovimientoStock::class, $mov);
        $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        $faena = $em->find(FaenaDiaria::class, $fanday);
        $valores = $movimiento->getValores()->getIterator();
        $valores->uasort( function ($a, $b) {
                                                  if ($a->getAtributo()->getNumeroOrden() == $b->getAtributo()->getNumeroOrden()) {
                                                      return 0;
                                                  }
                                                  return ($a->getAtributo()->getNumeroOrden() < $b->getAtributo()->getNumeroOrden()) ? -1 : 1;
                                              });
        $valores = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($valores));
        $movimiento->setValores($valores);
        $formAtr = $this->getFormAddMovStock($movimiento, $proceso, $articulo, 'bd_adm_edit_mov_stock_procesar', $faena);
        return $this->render('@GestionFaena/faena/editMovStock.html.twig', array('valores' => $valores, 'faena' => $faena, 'fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso));        
    }

    /**
     * @Route("/gstMovEditProc/{mov}/{proc}/{art}/{fanday}", name="bd_adm_edit_mov_stock_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarEditMovimientoStockAction(Request $request, $mov, $proc, $art, $fanday)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $movimiento = $em->find(MovimientoStock::class, $mov);
        $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        $faena = $em->find(FaenaDiaria::class, $fanday);
        $formAtr = $this->getFormAddMovStock($movimiento, $proceso, $articulo,'bd_adm_edit_mov_stock_procesar', $faena);
        $formAtr->handleRequest($request);
        $repo = $em->getRepository(MovimientoStock::class);
      //  $stock = $repo->pesoPromedio($proceso, $articulo)['valor'];
        $stock = 0;
        $movimiento->updateValues($stock, $em);
        if ($formAtr->isValid())
        {
            $proceso->setUltimoMovimiento(new \DateTime());
            $em->flush();
            return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fanday]);
        }
        return $this->render('@GestionFaena/faena/editMovStock.html.twig', array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso)); 
    }


    private function getFormDeleteMovimiento($mov, $trx, $faena)
    {
        $form = $this->createFormBuilder()
                      ->add('delete', SubmitType::class, ['label' => 'Eliminar']) 
                      ->setAction($this->generateUrl('bd_adm_mov_st_delete', array('mov' => $mov, 'trx' => $trx, 'faena' => $faena)))     
                      ->setMethod('DELETE')   
                      ->getForm();
        return $form;
    }
    /**
     * @Route("/gst/{mov}/{trx}/{faena}", name="bd_adm_mov_st_delete", methods={"DELETE"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function eliminarMovimientoStockAction($mov, $trx, $faena)
    {
        $em = $this->getDoctrine()->getManager();
        $movimiento = $em->find(MovimientoStock::class, $mov);
        $id = $movimiento->getProcesoFnDay()->getId();
        if ($trx)
        {
            $transferencia = $em->find(MovimientoCompuesto::class, $trx);
            $transferencia->getMovimientoOrigen()->setEliminado(true);
            $transferencia->getMovimientoDestino()->setEliminado(true);
        }
        else
        {
            $movimiento->setEliminado(true);
        }
        $em->flush();
        return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $id, 'fd' => $faena]);
    }



////////////////////Alta concepto movmiento
    /**
     * @Route("/gstaddconmov", name="bd_add_concepto_movimiento")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function generarNuevoConceptoMovimiento()
    {
        $concepto = new ConceptoMovimiento();
        $form = $this->getFormAltaConcepto($concepto);
        return $this->render('@GestionFaena/faena/addConcepto.html.twig', array('form' => $form->createView())); 
    }

    private function getFormAltaConcepto($concepto)
    {
        return $this->createForm(ConceptoMovimientoType::class, 
                                 $concepto, 
                                 ['action' => $this->generateUrl('bd_add_concepto_movimiento_procesar'),'method' => 'POST']);
    }

    /**
     * @Route("/gstaddconmovproc", name="bd_add_concepto_movimiento_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioConceptoMovimiento(Request $request)
    {
        $concepto = new ConceptoMovimiento();
        $form = $this->getFormAltaConcepto($concepto);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($concepto);
            $entityManager->flush();
            $this->addFlash(
                        'sussecc',
                        'Concepto generado exitosamente al proceso!'
                    );
            return $this->redirectToRoute('bd_add_concepto_movimiento');
        }
        return $this->render('@GestionFaena/faena/addConcepto.html.twig', array('form' => $form->createView()));
    }
//////////////////// fin alta concepto movimiento

    /**
     * @Route("/addProcUsr", name="conf_add_proc_user")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addProcedureUser()
    {
        $form = $this->getFormSele();
        return $this->render('@GestionFaena/addProcUser.html.twig', array('form' => $form->createView()));
    }

        /**
     * @Route("/addProcUsrProcesar", name="conf_add_proc_user_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addProcedureUserProcesar(Request $request)
    {
        $form = $this->getFormSele();
        $form->handleRequest($request);
        if ($form->isValid()){
          $data = $form->get('procesos')->getData();
          $entityManager = $this->getDoctrine()->getManager();
          $user = $form->get('usuario')->getData();
          $user->clearProcesos();
          foreach ($data as $key => $value) {
              $user->addProceso($value);
          }
          $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('@GestionFaena/addProcUser.html.twig', array('form' => $form->createView()));
    }

    private function getFormSele()
    {
        $form =    $this->createFormBuilder()
                         ->add('usuario', 
                              EntityType::class, 
                              [
                              'class' => 'AppBundle:User',
                                ])
                        ->add('procesos', 
                              EntityType::class, 
                              [
                              'class' => 'GestionFaenaBundle:ProcesoFaena',
                              'multiple' => true
                                ])
                        ->add('save', SubmitType::class, ['label' => 'Asignar Procesos'])
                        ->setAction($this->generateUrl('conf_add_proc_user_procesar'))
                        ->setMethod('POST')      
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/informes/ctrlam/{procFanDay}/{fanDay}", name="planilla_control_antemorten", methods={"GET", "POST"})
     */
    public function emitirCuponesAction($procFanDay, $fanDay, Request $request)
    {
        $proceso = $this->getDoctrine()->getManager()->find(ProcesoFaenaDiaria::class, $procFanDay);
        $faena = $this->getDoctrine()->getManager()->find(FaenaDiaria::class, $fanDay);

       // $proceso = $faena->getProceso(1);
        $logo = $this->get('kernel')->getRootDir() . '/../web/resources/img/senasa.jpg';
        $pdf = $this->get('app.fpdf');
        $pdf->AliasNbPages();


        $pdf->SetFont('Times','',12);
        $pdf->SetAutoPageBreak(false,0);  
        $pdf->AddPage('L', 'A4'); 
        $pdf->setX($pdf->getX() + 5);
        $pdf->Image($logo, $pdf->getX(), $pdf->getY(), 12, 17);
        $pdf->Line($pdf->getX()+12, $pdf->getY()+9, $pdf->getX()+150, $pdf->getY()+9);
        $pdf->Text($pdf->getX()+87, $pdf->getY()+3, "Centro Regional Buenos Aires Norte");
        $pdf->Text($pdf->getX()+37, $pdf->getY()+8, "Coordinacion Regional Tematica de Fiscalizacion Agroalimentaria");
        $pdf->SetFont('Times','',10);
        $pdf->Text($pdf->getX()+112, $pdf->getY()+13, "crfabanorte@senasa.gov.ar");
        $str = iconv('UTF-8', 'windows-1252', "Est. Nº Oficial 1567");
        $pdf->SetFont('Arial','',12);
        $pdf->Text($pdf->getX()+30, $pdf->getY()+23, $str);
        $pdf->Text($pdf->getX()+145, $pdf->getY()+23, "PLANILLA DE CONTROL  ANTE-MORTEM");
        $pdf->setXY($pdf->getX() - 5, $pdf->getY() + 23);
        $pdf->Rect($pdf->getX(), $pdf->getY()-5, $pdf->GetPageWidth() - 20, 7);
        $pdf->Rect($pdf->getX(), $pdf->getY()+2, $pdf->GetPageWidth() - 145, 7);
        $str = iconv('UTF-8', 'windows-1252', "ORDEN DE SERVICIO:");
        $pdf->Text($pdf->getX()+40, $pdf->getY()+7, $str." ".$faena->getFechaFaena()->format('m/Y'));
        $pdf->SetFont('Arial','',10);
        $h = 35;
        $str = iconv('UTF-8', 'windows-1252', "Nº de Orden");
        $pdf->TextWithDirection($pdf->getX()+10, $pdf->getY()+35, $str,'U');
        $pdf->Rect($pdf->getX(), $pdf->getY()+9, 20, $h);
        $str = iconv('UTF-8', 'windows-1252', "Nº DTA - Nº DTE");
        $pdf->Text($pdf->getX()+21, $pdf->getY()+27, $str);
        $pdf->Rect($pdf->getX()+20, $pdf->getY()+9, 34, $h);
        $pdf->Text($pdf->getX()+57, $pdf->getY()+27, "NOMBRE DE GRANJA");
        $pdf->Rect($pdf->getX()+54, $pdf->getY()+9, 50, $h);
        $str = iconv('UTF-8', 'windows-1252', "Nº de RENSPA");
        $pdf->Text($pdf->getX()+114, $pdf->getY()+27, $str);
        $pdf->Rect($pdf->getX()+104, $pdf->getY()+9, 48, $h);
        $pdf->TextWithDirection($pdf->getX()+162, $pdf->getY()+35, "Tipo de Aves",'U');
        $pdf->Rect($pdf->getX()+152, $pdf->getY()+2, 17, $h+7);

        $pdf->Text($pdf->getX()+170, $pdf->getY()+7, "Turno");
        $pdf->Text($pdf->getX()+230, $pdf->getY()+7, "Fecha");
        $pdf->Rect($pdf->getX()+169, $pdf->getY()+2, $pdf->GetPageWidth() - 189, 7);

        $str = iconv('UTF-8', 'windows-1252', "Nº DE AVES");
        $pdf->TextWithDirection($pdf->getX()+180, $pdf->getY()+36, $str,'U');
        $pdf->TextWithDirection($pdf->getX()+185, $pdf->getY()+33, "TOTALES",'U');
        $pdf->Rect($pdf->getX()+169, $pdf->getY()+9, 20, $h);

        $str = iconv('UTF-8', 'windows-1252', "Nº AVES MUERTAS");
        $pdf->TextWithDirection($pdf->getX()+198, $pdf->getY()+42, $str,'U');
        $pdf->TextWithDirection($pdf->getX()+203, $pdf->getY()+35, "EN JAULA",'U');
        $pdf->Rect($pdf->getX()+189, $pdf->getY()+9, 20, $h);

        $pdf->SetFont('Arial','',8);
        $pdf->Text($pdf->getX()+215, $pdf->getY()+14, "AVES ENFERMAS - SOSPECHOSAS");
        $pdf->Rect($pdf->getX()+209, $pdf->getY()+9, $pdf->GetPageWidth() - 229, 7);

        $pdf->Text($pdf->getX()+215, $pdf->getY()+19, "1 Sintomas respiratorios");
        $pdf->Rect($pdf->getX()+209, $pdf->getY()+16, $pdf->GetPageWidth() - 229, 4);

        $pdf->Text($pdf->getX()+215, $pdf->getY()+23, "2 Diarrea");
        $pdf->Rect($pdf->getX()+209, $pdf->getY()+20, $pdf->GetPageWidth() - 229, 4);

        $pdf->Text($pdf->getX()+215, $pdf->getY()+27, "3 Sintomas Nerviosos");
        $pdf->Rect($pdf->getX()+209, $pdf->getY()+24, $pdf->GetPageWidth() - 229, 4); 

        $pdf->Text($pdf->getX()+215, $pdf->getY()+31, "4 Plumaje erizado");
        $pdf->Rect($pdf->getX()+209, $pdf->getY()+28, $pdf->GetPageWidth() - 229, 4);

        $pdf->Text($pdf->getX()+215, $pdf->getY()+35, "5 Edema de Cabeza");
        $pdf->Rect($pdf->getX()+209, $pdf->getY()+32, $pdf->GetPageWidth() - 229, 4);

        $pdf->Text($pdf->getX()+215, $pdf->getY()+39, "6 Barbillones Inflamados");
        $pdf->Rect($pdf->getX()+209, $pdf->getY()+36, $pdf->GetPageWidth() - 229, 4); 

        $pdf->Text($pdf->getX()+215, $pdf->getY()+43, "7 Otros");
        $pdf->Rect($pdf->getX()+209, $pdf->getY()+40, $pdf->GetPageWidth() - 229, 4); 

        $pdf->setY($pdf->getY()+44);
        $i = 0;
        $h=6;

        $em = $this->getDoctrine()->getManager();
        $atr6 = $em->find(AtributoAbstracto::class, 6);
        $atr10 = $em->find(AtributoAbstracto::class, 10);
        $atr5 = $em->find(AtributoAbstracto::class, 5);
        $atr9 = $em->find(AtributoAbstracto::class, 9);
        $atr11 = $em->find(AtributoAbstracto::class, 11);

        foreach ($proceso->getMovimientos() as $mov) {
            if ($mov->getVisible() && (!$mov->getEliminado()) && (get_class($mov) == EntradaStock::class))
            {             
                          $i++;
                          $v = $mov->getValorWhitAtribute($atr6);
                          $data = "";
                          if ($v)
                              $data = $v->getData();
                          $pdf->Cell(20,$h,$data."",1,0,'C');
            
                          $v = $mov->getValorWhitAtribute($atr10);
                          $data = "";
                          if ($v)
                              $data = $v->getData();
                          $pdf->Cell(34,$h,$data."",1,0,'C');
            
                          $v = $mov->getValorWhitAtribute($atr5);
                          $data = "";
                          if ($v)
                              $data = $v->getData();
                          $pdf->Cell(50,$h,$data."",1,0,'C');
            
                          $v = $mov->getValorWhitAtribute($atr5);
                          $data = "RENSPA";
                          if ($v)
                              $data = $v->getEntidadExterna()->getRenspa();
                          $pdf->Cell(48,$h,$data."",1,0,'C');
            
                          $pdf->Cell(17,$h,$mov->getArtProcFaena()->getArticulo()."",1,0,'C');
            
                          $v = $mov->getValorWhitAtribute($atr9);
                          $data = "";
                          if ($v)
                              $data = $v->getData();
                          $pdf->Cell(20,$h,$data."",1,0,'C');
            
                          $v = $mov->getValorWhitAtribute($atr11);
                          $data = "";
                          if ($v)
                              $data = $v->getData();
                          $pdf->Cell(20,$h,$data."",1,0,'C');

                          $pdf->Cell($pdf->GetPageWidth() - 229,$h,"",1,1,'C');
             }
        }
        for ($j = $i; $j < 18; $j++)
            {             
                          $pdf->Cell(20,$h,"",1,0,'C');
                          $pdf->Cell(34,$h,"",1,0,'C');
                          $pdf->Cell(50,$h,"",1,0,'C');
                          $pdf->Cell(48,$h,"",1,0,'C');
                          $pdf->Cell(17,$h,"",1,0,'C');
                          $pdf->Cell(20,$h,"",1,0,'C');
                          $pdf->Cell(20,$h,"",1,0,'C');
                          $pdf->Cell($pdf->GetPageWidth() - 229,$h,"",1,1,'C');
             
        }
        return new Response($pdf->Output(), 200, array('Content-Type' => 'application/pdf'));  
    }
}
