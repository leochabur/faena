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
use GestionFaenaBundle\Entity\GrupoMovimientosAutomatico;
use GestionFaenaBundle\Entity\faena\MovimientoCompuesto;
use GestionFaenaBundle\Entity\faena\ConceptoMovimiento;
use GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso;
use GestionFaenaBundle\Entity\faena\ValorNumerico;
use GestionFaenaBundle\Entity\faena\ValorExterno;
use GestionFaenaBundle\Entity\PasoProcesoRealizado;
use GestionFaenaBundle\Entity\PasoProceso;
use GestionFaenaBundle\Entity\faena\MovimientoAutomatico;
use GestionFaenaBundle\Entity\faena\AtributoPorArticuloPorProceso;
use GestionFaenaBundle\Form\faena\AtributoPorArticuloPorProcesoType;
use GestionFaenaBundle\Entity\gestionBD\Granja;
use GestionFaenaBundle\Entity\gestionBD\Articulo;
use GestionFaenaBundle\Entity\gestionBD\Transportista;
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto;
use GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto;
use GestionFaenaBundle\Entity\gestionBD\AtributoMedibleManual;
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

  //////////////configura las caracteristicas de los articuslo que se veran en la vista del proceso faena

      /**
     * @Route("/setatrxdel/{id}", name="bd_add_atributo_por_proceso_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteAtributoPorArticuloPorProceso($id)
    {
        $em = $this->getDoctrine()->getManager();
        $atributo = $em->getRepository(AtributoPorArticuloPorProceso::class)->find($id);
        $em->remove($atributo);
        $em->flush();
        return $this->redirectToRoute('bd_add_atributo_por_proceso');
    }

      /**
     * @Route("/setatrxproc", name="bd_add_atributo_por_proceso")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAtributoPorArticuloPorProceso()
    {
        $em = $this->getDoctrine()->getManager();
        $lista = $em->getRepository(AtributoPorArticuloPorProceso::class)->findAll();
        $atributo = new AtributoPorArticuloPorProceso();
        $form = $this->createForm(AtributoPorArticuloPorProcesoType::class, 
                                $atributo, ['action' => $this->generateUrl('bd_add_atributo_por_proceso_procesar'),'method' => 'POST']);
        return $this->render('@GestionFaena/faena/addAtributosXArticuloXProceso.html.twig', array('lista' => $lista, 'form' => $form->createView()));
    }

      /**
     * @Route("/setatrxprocesar", name="bd_add_atributo_por_proceso_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAtributoPorArticuloPorProcesoProcesar(Request $request)
    {
        $atributo = new AtributoPorArticuloPorProceso();
        $form = $this->createForm(AtributoPorArticuloPorProcesoType::class, 
                                $atributo, ['action' => $this->generateUrl('bd_add_faena_diaria_procesar'),'method' => 'POST']);
        $form->handleRequest($request);
        if ($form->isValid())
        {
          $em = $this->getDoctrine()->getManager();
          $em->persist($atributo);
          $em->flush();
          return $this->redirectToRoute('bd_add_atributo_por_proceso');
        }
        return $this->render('@GestionFaena/faena/addAtributosXArticuloXProceso.html.twig', array('form' => $form->createView()));
    }

  //////////////////////////////////////////


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

        $allProcesos = $em->getRepository(ProcesoFaena::class)->findAllProcesos();

        foreach ($faena->getProcesos() as $p)
        {
            $procesosFd[] = $p;
        }

        $procesosFaltantes = array_diff($allProcesos, $procesosFd); //obtiene todos los proceso que falta instanciar como ProcesoFaenaDiaria para una faena dada

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

        $formPendientes = $this->getFormAddProcesoAFaena($procesosFaltantes, $fan);
        return $this->render('@GestionFaena/faena/procesosFaenaDiaria.html.twig', 
                              ['procesos' => $procesos, 'faena' => $faena, 'formPendientes' => $formPendientes->createView()]);
    }

    /**
     * @Route("/addprocatfd/{fd}", name="bd_add_prcessos_at_faena", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY') and is_granted('ROLE_SUPER_ADMIN')")
     */
    public function adddProcessAtFaena($fd, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(FaenaDiaria::class);
        $faena = $repo->find($fd);
        $allProcesos = $em->getRepository(ProcesoFaena::class)->findAllProcesos();
        $procesosFaltantes = array_diff($allProcesos, $faena->getProcesos()->toArray());
        $form = $this->getFormAddProcesoAFaena($procesosFaltantes, $fd);
        $form->handleRequest($request);
        if ($form->isValid()) 
        {
          $repository = $em->getRepository(ProcesoFaenaDiaria::class);
          $data = $form->getData();
          $proceso = $data['proceso'];
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
          $em->flush();
        }
        return $this->redirectToRoute('bd_view_procesos_faena_diaria', ['fan' => $fd]);
    }

    private function getFormAddProcesoAFaena($procesos, $faena)
    {
        $form =    $this->createFormBuilder()
                        ->add('proceso', 
                              EntityType::class, 
                              [
                              'class' => ProcesoFaena::class,
                              'choices' => $procesos,
                                ])
                        ->add('generar', SubmitType::class, ['label' => 'Generar Proceso']) 
                        ->setAction($this->generateUrl('bd_add_prcessos_at_faena', ['fd' => $faena]))
                        ->setMethod('POST')     
                        ->getForm();
        return $form;
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


    private function getFormBeginMovStockAction(ProcesoFaenaDiaria $proceso, $fd, $complete = true)
    {

        $form = $this->createFormBuilder();
        if ($complete)
        {
                   $form->add('tipoMovimiento', EntityType::class, ['class' => TipoMovimientoConcepto::class, 
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
          }
          if (count($proceso->getProcesoFaena()->getAutomaticos()))
          {
            $form->add('automatic', SubmitType::class, ['label' => 'Generar Movimientos >>']);
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
     * @Route("/genauto/{proc}/{fan}/{gpo}", name="bd_generate_movimientos_automaticos")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function generateMovimientosAutmaticos($proc, $fan, $gpo)
    {
      $em = $this->getDoctrine()->getManager();
      $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
      $faena = $em->find(FaenaDiaria::class, $fan);
      $procFaena = $proceso->getProcesoFaena();

      $grupoAutoomatico = $em->find(GrupoMovimientosAutomatico::class, $gpo);
      $automaticos = $grupoAutoomatico->getAutomaticos();
   //   throw new \Exception("HASTA ACXA PASO ".$automaticos->count());
      try
      {
          foreach ($automaticos as $movAut)
          {              
              $auto = $movAut->getArticuloAtributoConcepto();
              if (!$proceso->realizoMovimientoConArticuloAtriutoConcepto($auto))
              {
                  $instance = $auto->getConcepto()->getTipoMovimiento()->getInstancia();
                  if ($instance == 4)
                  {
                    $proceso = $this->procesarTransformarStock($proceso, $auto, $auto->getConcepto(), $faena, $em);
                  }
                  elseif($instance == 5)
                  {
                    //throw new \Exception("HASTA ACXA PASO instance 5");
                    $var = (bool)true;

                    $proceso = $this->procesarTransferirStock($proceso, $auto, $auto->getConcepto(), $faena, $em, $movAut, null, $var);
                  }
                  elseif (in_array($instance, [2,3])) 
                  {
                    $proceso = $this->procesarEntradaSalidaStock($proceso, $auto, $auto->getConcepto(), $faena, $instance, $em);               
                  }
              }
              else
              {
                $this->addFlash('errorLoad', ["El movimiento ".$auto->getVistaEdicion().", ya se ha realizado!"]);
                //$this->addFlash('error', "El movimiento ".$auto->getVistaEdicion().", ya se ha realizado!");
              }                             
          }
          $this->registrarPasoRealizado($grupoAutoomatico, $proceso->getProcesoFaena(), $proceso, $faena, $em);
          $em->flush(); 
      }
      catch(\Exception $e){
                            $this->addFlash('errorLoad', [$e->getMessage()]);
                            //throw $e;
                          }
      $proceso = $faena->getProceso($grupoAutoomatico->getProcesoFaena()->getId());

      $movAutoRealizados = $em->getRepository(GrupoMovimientosAutomatico::class)->findAllMovimientosRealizadosProceso($proceso, $faena);
      $realizados = [];
      foreach ($movAutoRealizados as $mr)
      {
        $realizados[$mr->getId()] = true;
      }

      $formsAutomaticos = [];
      foreach ($proceso->getProcesoFaena()->getAutomaticos() as $grupo)
      {
              $formsAutomaticos[$grupo->getId()] = $this->getFormExecuteAutomaticMove($proceso, $faena, $grupo)->createView();     
      }
      return $this->render('@GestionFaena/faena/generarMovimientosAutomaticos.html.twig', 
                          ['formsAuto' => $formsAutomaticos, 'faena' => $faena, 'proceso' => $proceso, 'realizados' => $realizados]);
      //return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proceso->getId(), 'fd' => $fan]);
    }

    /**
     * @Route("/updaterom/{proc}/{art}/{val}/{fd}/{base}", name="bd_adm_proc_romaneo_articulos", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function romaneoArticulo($proc, $art, $val, $fd, $base, Request $request)
    {
        $value = $request->request->get('data');

        $em = $this->getDoctrine()->getManager();
        $procesoFaenaDiaria = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);
        $procesoFaena = $procesoFaenaDiaria->getProcesoFaena();

        $articulo = $em->find(Articulo::class, $art); //el articulo final de la transformacion

        $articuloBase = $em->find(Articulo::class, $base); // articulo origen de la transformacion

        if ($val == 0) //no existe aun un valor para el articulo
        {
          try{
                //Debe generar una EntradaStock con el valor del articulo y una SalidaStock del articulo Base
                //recupera el concepto para
                $repoConcepto = $em->getRepository(ConceptoMovimiento::class);
                $conceptoMovimiento = $repoConcepto->getConceptoOfTransformacion();

                if (!$conceptoMovimiento)
                {
                  return new JsonResponse(['status' => false, 'message' => 'No se ha configurado un concepto para admitir transformaciones']);
                }

                //Atributo abstracto base definido en el Proceso Faena
               // $atributoAbstractoBase = $procesoFaena->getAtributoAbstractoBase();


                //debe recuperar el atributo del articulo destino de la transformacion
                $atributoDestino = $procesoFaena->existeArticuloDefinidoManejoStock($articulo);
                if (!$atributoDestino)
                {
                  return new JsonResponse(['status' => false, 'message' => 'El articulo '.$articulo.' no se encuentra configurado para manejar stock']);
                }
                $atributoDestino = $atributoDestino->getAtributo();

                $atributoAbstractoBase = $procesoFaena->existeArticuloDefinidoManejoStock($articuloBase);
                if (!$atributoAbstractoBase)
                {
                  return new JsonResponse(['status' => false, 'message' => 'El articulo '.$articuloBase.' no se encuentra configurado para manejar stock']);
                }
                $atributoAbstractoBase = $atributoAbstractoBase->getAtributo();

                //Atributo abstracto base definido en el Proceso Faena
              //  $articuloBase = $procesoFaena->getArticuloBase();
                if (!$articuloBase)
                {
                  return new JsonResponse(['status' => false, 'message' => 'No se ha configurado un articulo base en el proceso']);
                }

                $artAtrConEntrada = $this->getArticuloAtributoConceptoForMovimientoAction($articulo, 
                                                                                    $conceptoMovimiento, 
                                                                                    EntradaStock::getInstance(),
                                                                                    $procesoFaena,
                                                                                    $em);

            //    return new JsonResponse(['status' => false, 'message' => 'Atributo Base '.$atributoDestino]);

                $atributoEntrada = $artAtrConEntrada->getAtributoMedibleManualActivo($atributoDestino);
                if (!$atributoEntrada)//No existe un Atributo generado
                {
                    $atributoEntrada = new AtributoMedibleManual();
                    ///deberia agregar la unidad de medida
                    $atributoEntrada->setAcumula(true);
                    $atributoEntrada->setNombre('Peso');
                    $atributoEntrada->setAsignado(true);
                    $atributoEntrada->setArticuloAtrConc($artAtrConEntrada);
                    $atributoEntrada->setAtributoAbstracto($atributoDestino);
                    $em->persist($atributoEntrada);
                }
                $entrada = new EntradaStock();
                $entrada->setProcesoFnDay($procesoFaenaDiaria);
                $entrada->setFaenaDiaria($faena);
                $entrada->setArtProcFaena($artAtrConEntrada);
                $entrada->setVisible(true);
                $em->persist($entrada);

                $valor = new ValorNumerico();
                $valor->setAtributo($atributoEntrada);
                $valor->setMovimiento($entrada);
                $valor->setAtributoAbstracto($atributoDestino);
                $valor->setMostrar(true);
                $valor->setAcumula($atributoEntrada->getAcumula());
                $valor->setValor($value);
                $em->persist($valor);


                //debe generar ahora la salida de stock del articulo base
                $artAtrConSalida = $this->getArticuloAtributoConceptoForMovimientoAction($articuloBase, 
                                                                                    $conceptoMovimiento, 
                                                                                    SalidaStock::getInstance(),
                                                                                    $procesoFaena,
                                                                                    $em);
                $atributoSalida = $artAtrConSalida->getAtributoMedibleManualActivo($atributoAbstractoBase);
                if (!$atributoSalida)//No existe un Atributo generado
                {
                    $atributoSalida = new AtributoMedibleManual();
                    ///deberia agregar la unidad de medida
                    $atributoSalida->setAcumula(true);
                    $atributoSalida->setNombre('Peso');
                    $atributoSalida->setAsignado(true);
                    $atributoSalida->setArticuloAtrConc($artAtrConSalida);
                    $atributoSalida->setAtributoAbstracto($atributoAbstractoBase);
                    $em->persist($atributoSalida);
                }
                $salida = new SalidaStock();
                $salida->setProcesoFnDay($procesoFaenaDiaria);
                $salida->setFaenaDiaria($faena);
                $salida->setArtProcFaena($artAtrConSalida);
                $salida->setVisible(true);
                $em->persist($salida);

               // $ajuste = ($articulo->getPresentacionKg()?$articulo->getPresentacionKg():1);
                $valorAjustado = $value;//($ajuste*$value);

                $valorS = new ValorNumerico();
                $valorS->setAtributo($atributoSalida);
                $valorS->setMovimiento($salida);
                $valorS->setAtributoAbstracto($atributoAbstractoBase);
                $valorS->setMostrar(true);
                $valorS->setAcumula($atributoSalida->getAcumula());
                $valorS->setValor($valorAjustado);
                $em->persist($valorS);

                $entrada->setMovimientoAsociado($salida);
                $em->flush();
                $stock = $em->getRepository(MovimientoStock::class)->getStockArticulos($procesoFaenaDiaria, $articuloBase, $faena);
                return new JsonResponse(['status' => true, 'stb' => $stock['cantidad']]);
              }
              catch (\Exception $e){ return new JsonResponse(['status' => false, 'message' => $e->getMessage()]); }
        }
        else
        {
          try
          {
             $valor = $em->find(ValorNumerico::class, $val);
             $valor->setValor($value);

             $ajuste = ($articulo->getPresentacionKg()?$articulo->getPresentacionKg():1);
             $valorAjustado = (-1)*$value;//($ajuste*$value);

             $entrada = $valor->getMovimiento();
             $salida = $entrada->getMovimientoAsociado();
             if ($salida) //existe la salida asociada
             {
                //debe recuperar el articulo para asi poder traer el atribustro abstracto correspondiente, ya que se puede dar el caso que se transformen productos con distintos atributos abtrsctaos
                $articuloBase = $salida->getArtProcFaena()->getArticulo();

                ///ahora recupera el atributo abstracto de acuerdo al seteo de manejo de stock
                $atributoAbstractoBase = $procesoFaena->existeArticuloDefinidoManejoStock($articuloBase);

                $valorAsoc = $salida->getValorWhitAtribute($atributoAbstractoBase->getAtributo());
                $valorAsoc->setValor($valorAjustado);
             }
             $em->flush();
             $stock = $em->getRepository(MovimientoStock::class)->getStockArticulos($procesoFaenaDiaria, $articuloBase, $faena);
             return new JsonResponse(['status' => true, 'stb' => $stock['cantidad']]);
           }
           catch (\Exception $e){ return new JsonResponse(['status' => false, 'message' => $e->getMessage()]); }
        }

    }

    /**
     * @Route("/clasartbse/{proc}/{fd}/{art}", name="clasifiar_articulo_base")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function clasificarArticulosBase($proc, $fd, $art)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);
        $articulo = $em->find(Articulo::class, $art);

        $stock = $em->getRepository(MovimientoStock::class)->getStockArticulos($proceso, $articulo, $faena);

        $repoConcepto = $em->getRepository(ConceptoMovimiento::class);
        $conceptoMovimiento = $repoConcepto->getConceptoOfTransformacion();

        if (!$conceptoMovimiento)
        {
          throw new \Exception("no se encuentra el concepto del movimiento");
        }

        $articuloBase = $proceso->getProcesoFaena()->getArticuloBase();
        if (!$articuloBase)  
        {
          throw new \Exception("no se encuentra definido el articulo base en el proceso");
        }

        $maxInputs = 3;
        $categorias = [];
        $subcategorias = [];
        $data = [];
        $articulos = $em->getRepository(Articulo::class)->getListaArticulosConCategoria();
        $cantCateg = [];
        $cantSubcates = [];
        foreach ($articulos as $art)
        {   
          if ($articulo->getArticulosClasificables()->contains($art))
          {
              $procesoFaena = $proceso->getProcesoFaena();

              $factor = $procesoFaena->existeArticuloDefinidoManejoStock($art); //obtiene si el articulo se encuentra en el proceso para realizar el manejo de stock
              $valor = 0;
              if ($factor)
              {
                  $movimiento = $proceso->getMovimientosArticulo($art, $faena);
                  if ($movimiento)
                  {
                    $val = $movimiento->getValorWhitAtribute($factor->getAtributo());
                    if ($val)
                    {
                      $valor = $val;
                    }
                  }
              }
              ////finaliza recuperacion
              $idCat = $art->getCategoria()->getId();
              $idSub = $art->getSubcategoria()->getId();
              if (!array_key_exists($idCat, $cantCateg))
              {
                $cantCateg[$idCat] = 0;
              }
              $cantCateg[$idCat]++;
              if (!array_key_exists($idCat, $cantSubcates))
              {
                $cantSubcates[$idCat] = [];
              }
              if (!array_key_exists($idSub, $cantSubcates[$idCat]))
              {
                  $cantSubcates[$idCat][$idSub] = 0;
              }
              $cantSubcates[$idCat][$idSub]++;
              $categorias[$idCat] = $art->getCategoria();
              $subcategorias[$idSub] = $art->getSubcategoria();
              if (!array_key_exists($idCat, $data))
              {
                $data[$idCat] = [];
              }
              if (!array_key_exists($idSub, $data[$idCat]))
              {
                $data[$idCat][$idSub] = [];
              }

              $data[$idCat][$idSub][] = [0 => $art, 1 => $valor];
            }
        }
        $em->flush();
        return $this->render('@GestionFaena/faena/clasificarArticuloBase.html.twig', 
                            array( 'proceso' => $proceso, 
                                   'faena' => $faena, 
                                   'articulos' => $data, 
                                   'cates' => $categorias, 
                                   'subcates' => $subcategorias,
                                    'ccat' => $cantCateg,
                                    'csub' => $cantSubcates,
                                    'articulo' => $articulo,
                                    'stock' => $stock));
    }

    /**
     * @Route("/regvta/{proc}/{art}/{val}/{fd}/{cv}/{ent}", name="bd_adm_proc_registrar_venta", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function registrarVenta($proc, $art, $val, $fd, $cv, $ent, Request $request)
    {
        $value = $request->request->get('data');

        $em = $this->getDoctrine()->getManager();
        $procesoFaenaDiaria = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);
        $procesoFaena = $procesoFaenaDiaria->getProcesoFaena();

        $articulo = $em->find(Articulo::class, $art); //el articulo final de la transformacion

        if ($val == 0) //no existe aun un valor para la tupla Entidad-Articulo
        {
          try{
                if (!is_numeric($value))
                {
                    return new JsonResponse(['status' => false, 'message' => 'Debe ingresar un valor valido!']);
                }

                $conceptoVenta = $em->getRepository('GestionFaenaBundle\Entity\ConceptoVenta')->find($cv);
                //Debe generar una Salida de Stock
                //recupera el concepto para
                $conceptoMovimiento = $conceptoVenta->getConcepto();

                $entidad = $em->getRepository($conceptoVenta->getEntidadExterna())->find($ent);

                //debe generar ahora la salida de stock del articulo base
                $artAtrConSalida = $this->getArticuloAtributoConceptoForMovimientoAction($articulo, 
                                                                                    $conceptoMovimiento, 
                                                                                    SalidaStock::getInstance(),
                                                                                    $procesoFaena,
                                                                                    $em);

                $atributoAbstractoBase = $procesoFaena->existeArticuloDefinidoManejoStock($articulo);
                if (!$atributoAbstractoBase)
                {
                  return new JsonResponse(['status' => false, 'message' => 'El articulo '.$articulo.' no se encuentra configurado para manejar stock']);
                }
                $atributoAbstractoBase = $atributoAbstractoBase->getAtributo();

                $atributoSalida = $artAtrConSalida->getAtributoMedibleManualActivo($atributoAbstractoBase);

                if (!$atributoSalida)//No existe un Atributo generado
                {
                    $atributoSalida = new AtributoMedibleManual();
                    ///deberia agregar la unidad de medida
                    $atributoSalida->setAcumula(true);
                    $atributoSalida->setNombre('Peso');
                    $atributoSalida->setAsignado(true);
                    $atributoSalida->setArticuloAtrConc($artAtrConSalida);
                    $atributoSalida->setAtributoAbstracto($atributoAbstractoBase);
                    $em->persist($atributoSalida);
                }
                $salida = new SalidaStock();
                $salida->setProcesoFnDay($procesoFaenaDiaria);
                $salida->setFaenaDiaria($faena);
                $salida->setArtProcFaena($artAtrConSalida);
                $salida->setVisible(true);
                $em->persist($salida);

                ///Atributo correspondiente a la entidad
                $valorE = new ValorExterno();
                $valorE->setAtributoAbstracto($conceptoVenta->getAtributo());
                $valorE->setMovimiento($salida);
                $valorE->setMostrar(true);
                $valorE->setEntidadExterna($entidad);
                $em->persist($valorE);

                ///Atributo correspondiente al valor
                $valorS = new ValorNumerico();
                $valorS->setAtributo($atributoSalida);
                $valorS->setMovimiento($salida);
                $valorS->setAtributoAbstracto($atributoAbstractoBase);
                $valorS->setMostrar(true);
                $valorS->setAcumula($atributoSalida->getAcumula());
                $valorS->setValor($value);
                $em->persist($valorS);
                $em->flush();

                return new JsonResponse(['status' => true, 'idm' => $valorS->getId()]);
            }
            catch (\Exception $e){ return new JsonResponse(['status' => false, 'message' => $e->getMessage()]); }
        }
        else
        {
          try
          {
             if (!is_numeric($value))
             {
                return new JsonResponse(['status' => false, 'message' => 'Debe introducir un valor numerico']);
             }
             $valor = $em->find(ValorNumerico::class, $val);
             $valor->setValor((-1)*$value);
             $em->flush();
             return new JsonResponse(['status' => true]);
           }
           catch (\Exception $e){ return new JsonResponse(['status' => false, 'message' => $e->getMessage()]); }
        }

    }
    /**
     * @Route("/genvtas/{proc}/{fd}", name="generar_ventas", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function generarVentas($proc, $fd, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);
        
        $procesoFaena = $proceso->getProcesoFaena();

        $form = $this->getFormIngresarVenta($proceso, $faena);
        $form->handleRequest($request);
        if (!$form->isValid()) {
          return new Response('kakakakakakas');
        }

        $data = $form->getData();

        $conceptoMovimiento = $data['conceptoVenta'];
        if (!$conceptoMovimiento)
        {
          throw new \Exception("no se encuentra el concepto del movimiento");
        }
        $articuloBase = $proceso->getProcesoFaena()->getArticuloBase();
        if (!$articuloBase)  
        {
          throw new \Exception("no se encuentra definido el articulo base en el proceso");
        }

        $repository = $em->getRepository($data['conceptoVenta']->getEntidadExterna());
        $entidades = $repository->getActivos();

        $salidas = []; //almacena las salidas de stock de cada articulo correspondiente a cada entidad

        $valores = $em->getRepository(MovimientoStock::class)->getMovimientosConConcepto($proceso, $faena, $conceptoMovimiento->getConcepto());

        foreach ($valores as $v)
        {

            if (!array_key_exists($v[0]->getEntidadExterna()->getId(), $salidas)) //almacena una entidad externa
            {
                $salidas[$v[0]->getEntidadExterna()->getId()] = []; 
            }

            if (!array_key_exists($v['article'], $salidas[$v[0]->getEntidadExterna()->getId()])) //para una entidad externa almacena un articulo
            {
                $salidas[$v[0]->getEntidadExterna()->getId()][$v['article']] = [];
            } 

            $manejoStock = $procesoFaena->existeArticuloConIdDefinidoManejoStock($v['article']);  
            $movimiento = $v[0]->getMovimiento();     
            $val = $movimiento->getValorWhitAtribute($manejoStock->getAtributo());
            $valor = $val->getDataValue();
            $valor*= ($valor < 0?-1:1);
            $salidas[$v[0]->getEntidadExterna()->getId()][$v['article']] = ['id' => $val->getId(), 'data' => $valor];
        }





        $maxInputs = 3;
        $categorias = [];
        $subcategorias = [];
        $data = [];
        $articulos = $em->getRepository(Articulo::class)->getListaArticulosConCategoria();
        $cantCateg = [];
        $cantSubcates = [];
        foreach ($articulos as $art)
        {   
              $idCat = $art->getCategoria()->getId();
              $idSub = $art->getSubcategoria()->getId();
              if (!array_key_exists($idCat, $cantCateg))
              {
                $cantCateg[$idCat] = 0;
              }
              $cantCateg[$idCat]++;
              if (!array_key_exists($idCat, $cantSubcates))
              {
                $cantSubcates[$idCat] = [];
              }
              if (!array_key_exists($idSub, $cantSubcates[$idCat]))
              {
                  $cantSubcates[$idCat][$idSub] = 0;
              }
              $cantSubcates[$idCat][$idSub]++;
              $categorias[$idCat] = $art->getCategoria();
              $subcategorias[$idSub] = $art->getSubcategoria();
              if (!array_key_exists($idCat, $data))
              {
                $data[$idCat] = [];
              }
              if (!array_key_exists($idSub, $data[$idCat]))
              {
                $data[$idCat][$idSub] = [];
              }

              $data[$idCat][$idSub][] = [0 => $art];
        }
        $em->flush();
        return $this->render('@GestionFaena/faena/registrarVentasAP.html.twig', 
                            array( 'proceso' => $proceso, 
                                   'faena' => $faena, 
                                   'articulos' => $data, 
                                   'cates' => $categorias, 
                                   'subcates' => $subcategorias,
                                    'ccat' => $cantCateg,
                                    'csub' => $cantSubcates,
                                    'stock' => 0,
                                    'concepto' => $conceptoMovimiento,
                                    'entidades' => $entidades,
                                    'salidas' => $salidas));
        
    }

    private function getFormIngresarVenta(ProcesoFaenaDiaria $proceso, FaenaDiaria $faena)
    {
        $form =    $this->createFormBuilder()
                        ->add('conceptoVenta', 
                              EntityType::class, [
                              'class' => 'GestionFaenaBundle\Entity\ConceptoVenta',                          
                              'choices' => $proceso->getProcesoFaena()->getVentas()
                        ])
                        ->add('asignar', SubmitType::class, ['label' => 'Registrar'])    
                        ->setAction($this->generateUrl('generar_ventas', array('proc' => $proceso->getId(), 'fd' => $faena->getId())))  
                        ->setMethod('POST')               
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/gstProcFanDay/{proc}/{fd}/{typ}", name="bd_adm_proc_fan_day")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function administrarProcesoFaenaDiaria($proc, $fd, $typ = null)
    {   
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);
        $form = $this->getFormBeginMovStockAction($proceso, $fd);
        if ($proceso->getProcesoFaena()->getInstance() == 3)
        {
           return $this->getFormProccessMedium($proceso, $faena, $typ);
        }
        if ($proceso->getProcesoFaena()->getRomanea())
        {
            $repoMov = $em->getRepository(MovimientoStock::class);

            $clasificables = $repoMov->getStockArticulosClasificablesPorProcesoParaFaena($proceso->getProcesoFaena(), $faena);

            $iniciales = $repoMov->getStockArticulosPorProcesoAnteriorAFaena($proceso->getProcesoFaena(), $faena);
            $stockInicial = [];
            foreach ($iniciales as $init)
            {
              $stockInicial[$init['idArt']] = $init['cantidad'];
            }

            ///////////calcula el stock total de cada articulo
            $finales = $repoMov->getStockArticulosPorProcesoHastaFaena($proceso->getProcesoFaena(), $faena);
            $stockFinal = [];
            foreach ($finales as $fin)
            {
              $stockFinal[$fin['idArt']] = $fin['cantidad'];
            }
            /////////////////////////////////////

            $repoConcepto = $em->getRepository(ConceptoMovimiento::class);
            $conceptoMovimiento = $repoConcepto->getConceptoOfTransformacion();
            if (!$conceptoMovimiento)
            {
              throw new \Exception("no se encuentra el concepto del movimiento");
            }
            $articuloBase = $proceso->getProcesoFaena()->getArticuloBase();
            if (!$articuloBase)  
            {
              throw new \Exception("no se encuentra definido el articulo base en el proceso");
            }

            $maxInputs = 3;
            $categorias = [];
            $subcategorias = [];
            $data = [];
            $articulos = $em->getRepository(Articulo::class)->getListaArticulosConCategoria();
            $cantCateg = [];
            $cantSubcates = [];
            $arrayArticulos = [];
            foreach ($articulos as $art)
            {   
                $arrayArticulos[$art->getId()] = $art;
                $procesoFaena = $proceso->getProcesoFaena();

                $factor = $procesoFaena->existeArticuloDefinidoManejoStock($art); //obtiene si el articulo se encuentra en el proceso para realizar el manejo de stock

                $valor = 0;
                if ($factor)
                {
                    //$valor = $proceso->getStockArticuloConFaenaDiaria($faena, $art, $factor->getAtributo());
                    $movimiento = $proceso->getMovimientosArticulo($art, $faena);
                    if ($movimiento)
                    {
                      $val = $movimiento->getValorWhitAtribute($factor->getAtributo());
                      if ($val)
                      {
                        $valor = $val;
                      }
                    }
                }
             //   throw new \Exception("Articulo ".$art."  Factor ".$factor."  Valor ".$valor);
                ////finaliza recuperacion
                $idCat = $art->getCategoria()->getId();
                $idSub = $art->getSubcategoria()->getId();
                if (!array_key_exists($idCat, $cantCateg))
                {
                  $cantCateg[$idCat] = 0;
                }
                $cantCateg[$idCat]++;
                if (!array_key_exists($idCat, $cantSubcates))
                {
                  $cantSubcates[$idCat] = [];
                }
                if (!array_key_exists($idSub, $cantSubcates[$idCat]))
                {
                    $cantSubcates[$idCat][$idSub] = 0;
                }
                $cantSubcates[$idCat][$idSub]++;
                $categorias[$idCat] = $art->getCategoria();

                $subcategorias[$idSub] = $art->getSubcategoria();

                if (!array_key_exists($idCat, $data))
                {
                  $data[$idCat] = [];
                }
                if (!array_key_exists($idSub, $data[$idCat]))
                {
                  $data[$idCat][$idSub] = [];
                }

                $data[$idCat][$idSub][] = [0 => $art, 1 => $valor];
            }
            $em->flush();
            $ventasTotales = [];            
            $movimientosVenta = $repoMov->getAllMovimientosConConcepto($proceso, $faena);
            $procFaena = $proceso->getProcesoFaena();
            $conceptos = $procFaena->getConceptosOfVentas();
            $totalVentas = [];
            foreach ($movimientosVenta as $vta)
            {
                $art = $arrayArticulos[$vta['idArt']];
                $stock = $procesoFaena->existeArticuloDefinidoManejoStock($art);
                if ($stock)
                {
                  $mov = $vta[0];
                  $value = $mov->getValorWhitAtribute($stock->getAtributo());
                  if (!array_key_exists($vta['idConc'], $ventasTotales))
                  {
                    $ventasTotales[$vta['idConc']] = [];
                  }
                  if (!array_key_exists($vta['idArt'], $totalVentas))
                  {
                    $totalVentas[$vta['idArt']] = 0;
                  }
                  $totalVentas[$vta['idArt']]+= $value->getDataValue();
                  if (!array_key_exists($vta['idArt'], $ventasTotales[$vta['idConc']]))
                  {
                    $ventasTotales[$vta['idConc']][$vta['idArt']] = 0;
                  }
                  $ventasTotales[$vta['idConc']][$vta['idArt']]+= $value->getDataValue();

                }
            }
         
            $formMovimientos = $this->getFormBeginMovStockAction($proceso, $faena->getId(), false);

            return $this->render('@GestionFaena/faena/adminProcFanDayRomaneaAp.html.twig', 
                                array( 'proceso' => $proceso, 
                                       'formMovimientos' => $formMovimientos->createView(),
                                       'form' => $form->createView(), 
                                       'faena' => $faena, 
                                       'articulos' => $data, 
                                       'cates' => $categorias, 
                                       'subcates' => $subcategorias,
                                        'ccat' => $cantCateg,
                                        'csub' => $cantSubcates,
                                        'clasificables' => $clasificables,
                                        'si' => $stockInicial,
                                        'sf' => $stockFinal,
                                        'ventasTotales' => $ventasTotales,
                                        'conceptos' => $conceptos,
                                        'totVtas' => $totalVentas,
                                        'ventas' => $this->getFormIngresarVenta($proceso, $faena)->createView()));
        }

        $repository = $em->getRepository('GestionFaenaBundle:faena\MovimientoStock');
        $movimientos = $repository->findAllMovimientos($proceso);
        $conceptos = array();
        $movStock = array();
        $datos = array();
        $formsDelete = array();
        $totales = array();
        $concMovimientos = array();
        ///recupera todos loas pasos realizados para el proceso
        $pasosRealizados = $em->getRepository(PasoProcesoRealizado::class)->findPasosRealizadoProceso($proceso, $faena);
        $pasos = [];
        foreach ($pasosRealizados as $p)
        {
          $pasos[$p->getPaso()->getId()] = true;
        }
        ///////////////
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
        
        return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('con' => $concMovimientos, 'totales' =>$totales,'formsDelete' => $formsDelete, 'movs' => $movStock, 'conceptos' => $conceptos, 'datos' => $datos, 'movimientos' => $movimientos, 'proceso' => $proceso, 'form' => $form->createView(), 'faena' => $faena, 'pasos' => $pasos));
    }


    private function getFormExecuteAutomaticMove($proceso, $faena, $grupo)
    {
        
        $articulo = $grupo->getMovimientoManual()->getArticuloAtributoConcepto();

        if ($proceso->getProcesoFaena() != $articulo->getConcepto()->getProcesoFaena()) //quiere decir que debe desde un proceso, ejecutra una accion de otro proceso
        {
            $proceso = $faena->getProceso($articulo->getConcepto()->getProcesoFaena()->getId());
        }
        if ($grupo->getManual())
        {
          return  $this->createFormBuilder()
                       ->add('generar', SubmitType::class, ['label' => 'Generar'])
                       ->setAction($this->generateUrl('bd_adm_generate_mov_manual_from_automatic', 
                                                      ['proc' => $proceso->getId(), 'fd' => $faena->getId(), 'art' => $articulo->getId()]))
                       ->getForm();  
        }

        return  $this->createFormBuilder()
                     ->add('generar', SubmitType::class, ['label' => 'Generar'])
                     ->setAction($this->generateUrl('bd_generate_movimientos_automaticos', 
                                                    ['proc' => $proceso->getId(), 'fan' => $faena->getId(), 'gpo' => $grupo->getId()]))
                     ->getForm();     
    }

    /**
     * @Route("/genmanauto/{proc}/{fd}/{art}", name="bd_adm_generate_mov_manual_from_automatic")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function generateFormIngresoManualFromAutomatic($proc, $fd, $art)
    {
        $em = $this->getDoctrine()->getManager();
        $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);

        $tipoMovimiento = $articulo->getConcepto()->getTipoMovimiento();

        $movimiento = $tipoMovimiento->getInstanciaMovimiento();
        $movimiento->setProcesoFnDay($proceso);
        $movimiento->setFaenaDiaria($faena);
        $movimiento->setArtProcFaena($articulo);

        $movimiento->generateAtributes();
        $formAtr = $this->getFormAddMovStock($movimiento, $proceso, $articulo, 'bd_adm_proc_mov_st', $faena);
        return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'faena' => $faena));
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
              $movAutoRealizados = $em->getRepository(GrupoMovimientosAutomatico::class)->findAllMovimientosRealizadosProceso($proceso, $faena);
              $realizados = [];
              foreach ($movAutoRealizados as $mr)
              {
                $realizados[$mr->getId()] = true;
              }

              $formsAutomaticos = [];
              foreach ($proceso->getProcesoFaena()->getAutomaticos() as $grupo)
              {
                if (!$grupo->getEliminado())
                {
                  $formsAutomaticos[$grupo->getId()] = $this->getFormExecuteAutomaticMove($proceso, $faena, $grupo)->createView();
                }
              }
              return $this->render('@GestionFaena/faena/generarMovimientosAutomaticos.html.twig', 
                                  ['formsAuto' => $formsAutomaticos, 'faena' => $faena, 'proceso' => $proceso, 'realizados' => $realizados]);
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

    private function getFormProccessMedium(ProcesoFaenaDiaria $proceso, FaenaDiaria $faena, $typ = null)
    {
        $form = $this->getFormBeginMovStockAction($proceso, $faena->getId());
        ////actualizacion solo se mostraran las existencia para ver los movimientos se creara un linck en la vista donde se rediroccionara a dicha vista
        /// 2020-07-18
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(MovimientoStock::class);

        $headers = ['tipo' => ['data' => 'Detalle', 'numeric' => false], 
                    'art' => ['data' => 'Articulo', 'numeric' => false]
                   ];
        $body = [];
        $movimientos = $repo->findAllMovimientos($proceso);
        $informaTotales = false;
        $rounded = [];
        foreach ($movimientos as $mov) 
        {
            if (($mov->getFaenaDiaria() == $faena) || ($proceso->getProcesoFaena()->getPermanente() && (in_array($mov->getType(), [2,3]))) || ($proceso->getProcesoFaena()->getGeneraTransito()))
            {
              $art = $mov->getArtProcFaena()->getArticulo();
              if (!array_key_exists($art->getId(), $body))
              {
                  $body[$art->getId()] = ['tipo' => 'Existencias totales', 'art' => $art, 'trx' => 0];
              }
              foreach ($mov->getValores() as $valor) 
              {
                  if ($valor->isNumeric())
                  {
                    $atributo = ($valor->getAtributoAbstracto()?$valor->getAtributoAbstracto():$valor->getAtributo()->getAtributoAbstracto());
                    $define = $proceso->getProcesoFaena()->atributoAsignadoAArticulo($art, $atributo);
                    if (($define == 'N') || ($define))
                    {
                          if ($valor->getMostrar())
                          {      
                            
                            
                            $headers[$atributo->getId()] = ['data' => $atributo, 'numeric' => true];
                            
                          }
                          if (!array_key_exists($atributo->getId(), $body[$art->getId()]))
                          {
                                $body[$art->getId()][$atributo->getId()] = 0;
                          }
                          if ($valor->getAtributo())
                          {
                              if ($valor->getAcumula())
                              {
                                  $rounded[$art->getId()][$atributo->getId()] = $valor->getDecimales();
                                  $body[$art->getId()][$atributo->getId()]+= $valor->getData(true);
                              }
                          }
                          else
                          {
                            if ($valor->getMostrar())
                            {
                              $body[$art->getId()][$atributo->getId()]+= $valor->getData(true);
                              $rounded[$art->getId()][$atributo->getId()] = $valor->getDecimales();
                            }
                          }  
                      }                
                  }                  
              }
            }
        }
        foreach ($rounded as $kar => $var)
        {
            foreach ($var as $katr => $vatr)
            {
              if ($body[$kar][$katr] < 0.1)
              {
                $body[$kar][$katr] = 0.0;
              }
              
              $body[$kar][$katr] = number_format($body[$kar][$katr], $vatr,'.','');
            }
        }
        ///recupera todos loas pasos realizados para el proceso
        $pasosRealizados = $em->getRepository(PasoProcesoRealizado::class)->findPasosRealizadoProceso($proceso, $faena);
        $pasos = [];
        foreach ($pasosRealizados as $p)
        {
          $pasos[$p->getPaso()->getId()] = true;
        }
        ///////////////
      ///fin actualizacion
      //  $detalle = $this->getFormTipoMovimienos($proceso, $faena);
        $params = [
                      'form' => $form->createView(), 
                      'proceso' => $proceso,
                      'faena' => $faena,
                      'body' => $body,
                      'headers' => $headers,
                      'pasos' => $pasos
                  ];
        if ($typ)
        {
            $params['tipo'] = $typ;
        }
        return $this->render('@GestionFaena/faena/adminProcFanDayMedium.html.twig', $params);
    }

    //exclusivamente para el proceso de congelamiento
    /**
     * @Route("/getrmprmd/{proc}/{fd}/{art}", name="bd_adm_romanea_articulos_proceso_medium")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function getFormRomaneaProcesoMedium($proc, $fd, $art)
    {
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $em->find(FaenaDiaria::class, $fd);
        $repoMov = $em->getRepository(MovimientoStock::class);

        $clasificables = $repoMov->getStockArticulosClasificablesPorProcesoParaFaena($proceso->getProcesoFaena(), $faena);

        $iniciales = $repoMov->getStockArticulosPorProcesoAnteriorAFaena($proceso->getProcesoFaena(), $faena);
        $stockInicial = [];
        foreach ($iniciales as $init)
        {
          $stockInicial[$init['idArt']] = $init['cantidad'];
        }

        ///////////calcula el stock total de cada articulo
        $finales = $repoMov->getStockArticulosPorProcesoHastaFaena($proceso->getProcesoFaena(), $faena);
        $stockFinal = [];
        foreach ($finales as $fin)
        {
          $stockFinal[$fin['idArt']] = $fin['cantidad'];
        }
        /////////////////////////////////////

        $repoConcepto = $em->getRepository(ConceptoMovimiento::class);
        $conceptoMovimiento = $repoConcepto->getConceptoOfTransformacion();
        if (!$conceptoMovimiento)
        {
          throw new \Exception("no se encuentra el concepto del movimiento");
        }
        $articuloBase = $proceso->getProcesoFaena()->getArticuloBase();
        if (!$articuloBase)  
        {
          throw new \Exception("no se encuentra definido el articulo base en el proceso");
        }

        $maxInputs = 3;
        $categorias = [];
        $subcategorias = [];
        $data = [];
        $articulos = $em->getRepository(Articulo::class)->getListaArticulosConCategoria();
        $cantCateg = [];
        $cantSubcates = [];
        $arrayArticulos = [];
        foreach ($articulos as $art)
        {   
            $arrayArticulos[$art->getId()] = $art;
            $procesoFaena = $proceso->getProcesoFaena();

            $factor = $procesoFaena->existeArticuloDefinidoManejoStock($art); //obtiene si el articulo se encuentra en el proceso para realizar el manejo de stock

            $valor = 0;
            if ($factor)
            {
                //$valor = $proceso->getStockArticuloConFaenaDiaria($faena, $art, $factor->getAtributo());
                $movimiento = $proceso->getMovimientosArticulo($art, $faena);
                if ($movimiento)
                {
                  $val = $movimiento->getValorWhitAtribute($factor->getAtributo());
                  if ($val)
                  {
                    $valor = $val;
                  }
                }
            }
         //   throw new \Exception("Articulo ".$art."  Factor ".$factor."  Valor ".$valor);
            ////finaliza recuperacion
            $idCat = $art->getCategoria()->getId();
            $idSub = $art->getSubcategoria()->getId();
            if (!array_key_exists($idCat, $cantCateg))
            {
              $cantCateg[$idCat] = 0;
            }
            $cantCateg[$idCat]++;
            if (!array_key_exists($idCat, $cantSubcates))
            {
              $cantSubcates[$idCat] = [];
            }
            if (!array_key_exists($idSub, $cantSubcates[$idCat]))
            {
                $cantSubcates[$idCat][$idSub] = 0;
            }
            $cantSubcates[$idCat][$idSub]++;
            $categorias[$idCat] = $art->getCategoria();

            $subcategorias[$idSub] = $art->getSubcategoria();

            if (!array_key_exists($idCat, $data))
            {
              $data[$idCat] = [];
            }
            if (!array_key_exists($idSub, $data[$idCat]))
            {
              $data[$idCat][$idSub] = [];
            }

            $data[$idCat][$idSub][] = [0 => $art, 1 => $valor];
        }
        $em->flush();
        $ventasTotales = [];            
        $movimientosVenta = $repoMov->getAllMovimientosConConcepto($proceso, $faena);
        $procFaena = $proceso->getProcesoFaena();
        $conceptos = $procFaena->getConceptosOfVentas();
        $totalVentas = [];
        foreach ($movimientosVenta as $vta)
        {
            $art = $arrayArticulos[$vta['idArt']];
            $stock = $procesoFaena->existeArticuloDefinidoManejoStock($art);
            if ($stock)
            {
              $mov = $vta[0];
              $value = $mov->getValorWhitAtribute($stock->getAtributo());
              if (!array_key_exists($vta['idConc'], $ventasTotales))
              {
                $ventasTotales[$vta['idConc']] = [];
              }
              if (!array_key_exists($vta['idArt'], $totalVentas))
              {
                $totalVentas[$vta['idArt']] = 0;
              }
              $totalVentas[$vta['idArt']]+= $value->getDataValue();
              if (!array_key_exists($vta['idArt'], $ventasTotales[$vta['idConc']]))
              {
                $ventasTotales[$vta['idConc']][$vta['idArt']] = 0;
              }
              $ventasTotales[$vta['idConc']][$vta['idArt']]+= $value->getDataValue();

            }
        }
        return $this->render('@GestionFaena/faena/adminProcFanDayRomaneaAp.html.twig', 
                            array( 'proceso' => $proceso, 
                                   'form' => $form->createView(), 
                                   'faena' => $faena, 
                                   'articulos' => $data, 
                                   'cates' => $categorias, 
                                   'subcates' => $subcategorias,
                                    'ccat' => $cantCateg,
                                    'csub' => $cantSubcates,
                                    'clasificables' => $clasificables,
                                    'si' => $stockInicial,
                                    'sf' => $stockFinal,
                                    'ventasTotales' => $ventasTotales,
                                    'conceptos' => $conceptos,
                                    'totVtas' => $totalVentas,
                                    'ventas' => $this->getFormIngresarVenta($proceso, $faena)->createView()));
    }

    //////////VIEW DETALLE DE MOVIMIENTOS
   /**
     * @Route("/detamov/{proc}/{fd}/{art}", name="bd_adm_get_detalle_movimientos_proceso")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function detalleMovimientos($proc, $fd, $art)
    {   
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $articulo = $em->find(Articulo::class, $art);
        $faena = $em->find(FaenaDiaria::class, $fd);
        $repo = $em->getRepository(MovimientoStock::class); 
        $formsDelete = [];
                   
        $movimientos = $repo->findAllMovimientos($proceso);
        $headers = ['tipo' => ['data' => 'Tipo Movimiento', 'numeric' => false], 
                    'conc' => ['data'=> 'Concepto', 'numeric' => false], 
                    'art' => ['data' => 'Articulo', 'numeric' => false]
                   ];
        $body = [];
        $i = 0;
        $totales = ['tipo' => 'TOTALES', 'conc' => '', 'art' => ''];
        $informaTotales = false;
        if ($articulo)
        {
          $informaTotales = true;
        }
        $rounded = [];
        foreach ($movimientos as $mov) 
        {
            if (($mov->getFaenaDiaria() == $faena) || ($proceso->getProcesoFaena()->getPermanente() && (in_array($mov->getType(), [2,3]))) || ($proceso->getProcesoFaena()->getGeneraTransito()))
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
                  $nombreFaena = "";
                  if ($mov->getProcesoFnDay()->getProcesoFaena()->getPermanente())
                  {
                    $nombreFaena = " (Faena: ".$mov->getFaenaDiaria().")";
                  }

                  $movOrigen = $mov->getOrigen();
                  $from = "";

                  if ($mov->getOrigen())
                  {
                      if ($mov->getOrigen()->getInstance() == 4)
                      {
                         $movComp = $mov->getOrigen();
                         $from = " (".$movComp->getMovimientoOrigen()->getArtProcFaena()->getArticulo()." >> ".$movComp->getMovimientoDestino()->getArtProcFaena()->getArticulo().")";
                      }
                      elseif ($mov->getOrigen()->getInstance() == 5)
                      {
                         $movComp = $mov->getOrigen();
                         $from = " (".$movComp->getMovimientoOrigen()->getProcesoFnDay()." >> ".$movComp->getMovimientoDestino()->getProcesoFnDay().")";
                      }
                  }
                  elseif ($mov->getDestino())
                  {
                      if ($mov->getDestino()->getInstance() == 4)
                      {
                         $movComp = $mov->getDestino();
                         $from = " (".$movComp->getMovimientoOrigen()->getArtProcFaena()->getArticulo()." >> ".$movComp->getMovimientoDestino()->getArtProcFaena()->getArticulo().")";
                       }
                      elseif ($mov->getDestino()->getInstance() == 5)
                      {
                         $movComp = $mov->getDestino();
                         $from = " (".$movComp->getMovimientoOrigen()->getProcesoFnDay()." >> ".$movComp->getMovimientoDestino()->getProcesoFnDay().")";
                      }
                  }

                  $idTrx = ($mov->getOrigen()?$mov->getOrigen()->getId():($mov->getDestino()?$mov->getDestino()->getId():0));  
                  $formsDelete[$mov->getId()] = $this->getFormDeleteMovimiento($mov->getId(), $idTrx, $fd)->createView();

                  $body[$i] = ['tipo' => $mov.$nombreFaena, 'conc' => $mov->getArtProcFaena()->getConcepto()->getConcepto()."$from"];
                  $body[$i]['id'] = $mov->getId();
                  $body[$i]['trx'] = $idTrx;
                  $body[$i]['proc'] = $mov->getProcesado();
                  foreach ($mov->getValores() as $valor) 
                  {
                      $atributo = ($valor->getAtributo()?$valor->getAtributo()->getAtributoAbstracto():$valor->getAtributoAbstracto());
                      $define = $proceso->getProcesoFaena()->atributoAsignadoAArticulo($articulo, $atributo);
                      if (($define == 'N') || ($define))
                      {
                          if ($valor->getMostrar())
                          {                            
                              $headers[$atributo->getId()] = ['data' => $atributo, 'numeric' => $valor->isNumeric(), 'decimales' => 2];
                          }

                          $rounded[$atributo->getId()] = $valor->getDecimales();

                          $body[$i][$atributo->getId()] = $valor->getData()."";
                          $body[$i]['art'] = $mov->getArtProcFaena()->getArticulo();
                          if ($acumular)
                          {                        
                              $rounded[$atributo->getId()] = $valor->getDecimales();
                              if (!array_key_exists($atributo->getId(), $totales))
                              {
                                  $totales[$atributo->getId()] = 0;
                              }

                              if ($valor->getAtributo())
                              {
                                if ($valor->getAcumula())
                                {
                                  $totales[$atributo->getId()]+= $valor->getData();
                                }
                              }
                              else
                              {
                                $totales[$atributo->getId()]+= $valor->getData();
                              }
                          }        
                    }             
                  }                          
                  $i++;
                }
              }
        }
        
        $params = [ 'proceso' => $proceso,
                    'faena' => $faena,
                    'body' => $body,
                    'headers' => $headers
                   ];
        if ($informaTotales)
        {
            foreach ($rounded as $k => $v)
            {
              if ($totales[$k] < 0.1) //para corregir errores de redonedo y que no queden valores despreciables en la tabla
                $totales[$k] = 0;

              $totales[$k] = number_format($totales[$k], $v);
            }
            $params['totales'] = $totales;
        }
        
        if (count($formsDelete))
        {
          $params['formsDelete'] = $formsDelete;
        }
        return $this->render('@GestionFaena/faena/detalleMovimientosProceso.html.twig', $params);
    }
    /////////////////////////////

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
                          'conc' => ['data'=> 'Concepto', 'numeric' => false], 
                          'art' => ['data' => 'Articulo', 'numeric' => false]
                         ];
              $body = [];
              $i = 0;
              $totales = ['tipo' => 'TOTALES', 'conc' => '', 'art' => ''];
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
                    $formsDelete[$mov->getId()] = $this->getFormDeleteMovimiento($mov->getId(), $idTrx, $fd, $data['tipoMovimiento'])->createView();

                    $body[$i] = ['tipo' => $mov, 'conc' => $mov->getArtProcFaena()->getConcepto()->getConcepto().""];
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
                    'headers' => $headers,
                    'tipo' => $data['tipoMovimiento']
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
                                              $em,
                                              Request $request = null)
    {
              $stock = 0;
              $movimiento = new TransformarStock(); 
              $movimiento->setArtProcFaena($articulo);
              $movimiento->setProcesoFnDay($proceso);
              $movimiento->setFaenaDiaria($faena);
              $movimiento->generateAtributes();
              $formAtr = $this->getFormAddMovStock($movimiento, $proceso, $articulo, 'bd_adm_proc_mov_st', $faena);
              if ($request)
              {
                $formAtr->handleRequest($request);
              }
              $movimiento->updateValues($stock, $em);


              ///////////Debe quitar del stock el articulo base   (Ejemplo: Aves -> Corazon)
              ///Verifica si el Articulo base del proceso se encuentra configurado para manejar el stock
              $articuloBase = $articulo->getArticuloOrigenTransformacion();
              $articuloBaseManejaStock = $proceso->getProcesoFaena()->existeArticuloDefinidoManejoStock($articuloBase); //articulo base 

              $articuloDestino = $articulo->getArticulo();
              $articuloDestinoManejaStock = $proceso->getProcesoFaena()->existeArticuloDefinidoManejoStock($articuloDestino); //articulo destino 

              if (!$articuloBaseManejaStock) //no esta configurado el articulo base para manejar el stock en el proceso
              {
               // $this->addFlash('error', "El articulo ".$articuloBase." no se encuentra definido para manejar stock");
                throw new \Exception("El articulo ".$articuloBase." no se encuentra definido para manejar stock");
                //return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', ['fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'faena' => $faena]);
              }

              if (!$articuloDestinoManejaStock) //no esta configurado el articulo destino para manejar el stock en el proceso
              {
                //$this->addFlash('error', "El articulo ".$articuloDestino." no se encuentra definido para manejar stock");
                throw new \Exception("El articulo ".$articuloDestino." no se encuentra definido para manejar stock");
                //return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', ['fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'faena' => $faena]);
              }

              //1º: Recupera del proceso, el ArticuloAtributoConcepto para el TipoMovimiento-> Salida Stock
              $artAtrConSalida = $this->getArticuloAtributoConceptoForMovimientoAction($articuloBase,
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
              $salida->setProcesoFnDay($proceso);
              $salida->setArtProcFaena($artAtrConSalida);


              foreach ($movimiento->getValores() as $valor) {
                  $valorAtr = new ValorNumerico();
                  $valorAtr->setAtributoAbstracto($valor->getAtributo()->getAtributoAbstracto());
                  $valorAtr->setValor($valor->getValor());
                  $valorAtr->setUnidadMedida($valor->getUnidadMedida());
                  $valorAtr->setMostrar($valor->getAtributo()->getMostrar());
                  $valorAtr->setDecimales($valor->getAtributo()->getDecimales());
                  $valorAtr->setAcumula($valor->getAtributo()->getAcumula());
                  $salida->addValore($valorAtr);
              }

              $em->persist($salida);
              $proceso->addMovimiento($salida);
              ///Una vez realizada la salida delstock del articulo base, debe proceder a realizar la entrada del articulo destino (Corazon)
              //1º: Recupera del proceso, el ArticuloAtributoConcepto para el TipoMovimiento-> Entrada Stock
              $artAtrConEntrada = $this->getArticuloAtributoConceptoForMovimientoAction($articuloDestino,
                                                                                  $articulo->getConcepto()->getConcepto(),
                                                                                  EntradaStock::getInstance(),
                                                                                  $proceso->getProcesoFaena(),
                                                                                  $em);
              $entrada = new EntradaStock();
              $entrada->setFaenaDiaria($faena);
           //   $entrada->addValore($valorAtr);
              $entrada->setProcesoFnDay($proceso);
              $entrada->setArtProcFaena($artAtrConEntrada);
              foreach ($movimiento->getValores() as $valor) 
              {
                  $valorAtr = new ValorNumerico();
                  $valorAtr->setAtributoAbstracto($valor->getAtributo()->getAtributoAbstracto());
                  $dataValue = $valor->getValor();
                  $valorAtr->setValor($dataValue);
                  $valorAtr->setUnidadMedida($valor->getUnidadMedida());
                  $valorAtr->setMostrar($valor->getAtributo()->getMostrar());
                  $valorAtr->setDecimales($valor->getAtributo()->getDecimales());
                  $valorAtr->setAcumula($valor->getAtributo()->getAcumula());
                  $entrada->addValore($valorAtr);
              }

              $em->persist($entrada);
              $proceso->addMovimiento($entrada);

              $movimiento->setMovimientoDestino($entrada);
              $movimiento->setMovimientoOrigen($salida);
              $em->persist($movimiento);
              $proceso->addMovimiento($movimiento);
              return $proceso;
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
           /* $this->addFlash(
                              'errorLoad',
                              implode(' - ',$valid['messages'])
                            );*/
            throw new \Exception(implode(' - ',$valid['messages']));
           // return $this->render('@GestionFaena/faena/adminProcFanDay.html.twig', 
                             //   array('fatr' => $formAtr->createView(), 'movimiento' => $movimiento, 'proceso' => $proceso, 'faena' => $faena));                    
        }
        $em->persist($movimiento);
        $proceso->addMovimiento($movimiento);
        return $proceso;
    }

    /**
     * @Route("/nextmove/{id}", name="bd_adm_next_move")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function avanzarMovimiento($id) //para el proceso de Transito Congelado avanza los articulos al proceso de camara donde seran romaneados
    {
      try
      {
        $em = $this->getDoctrine()->getManager();

        //MOVIMIENTO ORIGINAL
        $movimiento = $em->find(MovimientoStock::class, $id);
        $procesoFaenaDiaria = $movimiento->getProcesoFnDay();
        $procesoFaena = $procesoFaenaDiaria->getProcesoFaena();        
        $articuloAtributoConcepto =  $movimiento->getArtProcFaena();
        $conceptoMovimientoProceso = $articuloAtributoConcepto->getConcepto();
        $faena = $movimiento->getFaenaDiaria();

        //para que no aparezca el ingreso desde el proceso origen - EJ si se incorporo un cajon de pollo congelado de empaque, una vez se avanze el mismo a camaras no debera aparecer en el listado por eso se marca como procesado
        $movimiento->setProcesado(true);
        $procesoDestino = $procesoFaena->getProcesosDestinoDefault();
        if (!$procesoDestino)
        {
            return new JsonResponse(['status' => false, 'message' => 'El proceso '.$procesoFaena.' no tiene configurado un proceso destino por defecto!']);
        }

        $articuloManejaStock = $procesoDestino->existeArticuloDefinidoManejoStock($articuloAtributoConcepto->getArticulo());
        if (!$articuloManejaStock) //no esta configurado el articulo para manejar el stock
        {
           return new JsonResponse(['status' => false, 'message' => "El articulo ".$articuloAtributoConcepto->getArticulo()." no se encuentra definido en el proceso ".$procesoDestino." para manejar stock"]);
        }

        //Busca en la FaenaDiaria correspondiente el ProcesoFaenaDiaria correspondiente al ProcesoFanea
        $procesoFaenaDiariaDestino = $faena->getProceso($procesoDestino->getId());
        if (!$procesoFaenaDiariaDestino) //no esta configurado el articulo para manejar el stock
        {
            return new JsonResponse(['status' => false, 'message' => 'El proceso de destino es inexistente!!!']);
        }

        //del movimiento actual recupera el ARTATRCONC sino lo crea
        $artAtrConOrigen = $this->getArticuloAtributoConceptoForMovimientoAction($articuloAtributoConcepto->getArticulo(),
                                                                                 $conceptoMovimientoProceso->getConcepto(),
                                                                                 SalidaStock::getInstance(),
                                                                                 $procesoFaena,
                                                                                 $em);

        $artAtrConTrx = $this->getArticuloAtributoConceptoForMovimientoAction($articuloAtributoConcepto->getArticulo(),
                                                                              $conceptoMovimientoProceso->getConcepto(),
                                                                              TransferirStock::getInstance(),
                                                                              $procesoFaena,
                                                                              $em);
        $transferencia = new TransferirStock(); 
        $transferencia->setArtProcFaena($artAtrConTrx);
        $transferencia->setProcesoFnDay($procesoFaenaDiaria);
        $transferencia->setFaenaDiaria($faena);
        $transferencia->setVisible(false);
        $transferencia->setProcesado(true);

        $salida = new SalidaStock();
        $salida->setFaenaDiaria($faena);
        $salida->setProcesoFnDay($procesoFaenaDiaria);
        $salida->setArtProcFaena($artAtrConOrigen); 
        $salida->setProcesado(true);


        //debe recuperar el ProcesoFaenaDiaria correspondiente a la faena y al proceso de Destino
        //$procesoFaenaDiariaDestino = $em->getRepository(ProcesoFaenaDiaria::class)->getProcesoFaenaDiariaWhitProcess($procesoDestino);

        $artAtrConDestino = $this->getArticuloAtributoConceptoForMovimientoAction($articuloAtributoConcepto->getArticulo(),
                                                                                   $conceptoMovimientoProceso->getConcepto(),
                                                                                   EntradaStock::getInstance(),
                                                                                   $procesoDestino,
                                                                                   $em);
        $entrada = new EntradaStock();
        $entrada->setFaenaDiaria($faena);
        $entrada->setProcesoFnDay($procesoFaenaDiariaDestino);
        $entrada->setArtProcFaena($artAtrConDestino);
        $entrada->setProcesado(true);

        ///copia los valores del movimiento orginial a los nuevos movimientos
        foreach ($movimiento->getValores() as $valor)
        {
           // if (!$valor->getAtributo())
         //   throw new \Exception("valor ".$valor->getId());
            $valorAtr = new ValorNumerico();
            $valorAtr->setAtributoAbstracto(($valor->getAtributo()?$valor->getAtributo()->getAtributoAbstracto():$valor->getAtributoAbstracto()));
            $dataValue = $valor->getValor();
            $valorAtr->setValor($dataValue);
            $valorAtr->setUnidadMedida($valor->getUnidadMedida());
            $valorAtr->setMostrar(true);
            $valorAtr->setDecimales(0);
            $valorAtr->setAcumula(true);

            $vSalida = clone $valorAtr;
            $vEntrada = clone $valorAtr;
            $vTrx = clone $valorAtr;
            $salida->addValore($vSalida);
            $entrada->addValore($vEntrada);
            $transferencia->addValore($vTrx);
        }
        
        $em->persist($transferencia);
        $em->persist($entrada);
        $em->persist($salida);
        $transferencia->setMovimientoDestino($entrada);
        $transferencia->setMovimientoOrigen($salida);              
        $em->persist($transferencia);
        $procesoFaenaDiaria->addMovimiento($transferencia);
        $procesoFaenaDiaria->setUltimoMovimiento(new \DateTime());
        $em->flush();
        return new JsonResponse(['status' => true]);
      }
      catch (\Exception $e)
      {
            return new JsonResponse(['status' => false, 'message' => 'Se produjo un error al procesar la solicitud!!']);
      }
    }


    private function procesarTransferirStock(ProcesoFaenaDiaria $proceso,
                                              ArticuloAtributoConcepto $articulo,
                                              ConceptoMovimientoProceso $concepto,
                                              FaenaDiaria $faena,
                                              $em,
                                              MovimientoAutomatico $movAutomatico, 
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
        $movimiento->setVisible(false);
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
           if ($automatico == true) 
           {
              if ((!$movAutomatico) || (!$movAutomatico->getProcesoDestinoDefault())) //Sino existe la definicion de Movimiento Automatico o esta no tiene un proceso destino por default...debe devolver error
              {
                  throw new \Exception("No se ha configurado correctamente la opcion dde movimientos automaticos!!!");
              }
              else
              {
                  $procesoDestino = $faena->getProceso($movAutomatico->getProcesoDestinoDefault()->getId());       
                  if (!($procesoDestino))
                  {
                    throw new \Exception("No se encuentra generado el proceso ".$movAutomatico->getProcesoDestinoDefault()." para la faena del dia ".$faena);
                  }          
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
                  throw new \Exception("El articulo ".$articulo->getArticulo()." no se encuentra definido en el proceso ".$procesoDestino." para manejar stock");
                }

                //Busca en la FaenaDiaria correspondiente el ProcesoFaenaDiaria correspondiente al ProcesoFanea
                $procFanDay = $faena->getProceso($procesoDestino->getProcesoFaena()->getId());
                if (!$procFanDay) //no esta configurado el articulo para manejar el stock
                {
                    throw new \Exception("El proceso de destino es inexistente!!!");
                }
                try
                {
                    //1º: Recupera del proceso destino, el ArticuloAtributoConcepto para el TipoMovimiento-> Entrada Stock
                    $artAtrConDestino = $this->getArticuloAtributoConceptoForMovimientoAction($articulo->getArticulo(),
                                                                                        $articulo->getConcepto()->getConcepto(),
                                                                                        EntradaStock::getInstance(),
                                                                                        $procesoDestino->getProcesoFaena(),
                                                                                        $em);
                }
                catch (\Exception $e){throw $e;}
                /*busca en la lista de valores de atributos del movimiento si existe el valor correspondiente al AtributoAbstracto 
                  que maneja el stock del proceso*/
                $valorAtributo = $movimiento->getValorWhitAtribute($articuloManejaStock->getAtributo());
                
                if (!$valorAtributo)
                {
                  throw new \Exception("No se encuentra el atributo en la lista de valores del movimiento!! ".$articuloManejaStock);
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

                /////////CAHNCHADA HAY QUE CORREJIRLO
                $kilos = 0;
                $cantidad = 0;
                ////////////////////////////////////
                foreach ($movimiento->getValores() as $valor) {
                    $valorAtr = new ValorNumerico();
                    $valorAtr->setAtributoAbstracto($valor->getAtributo()->getAtributoAbstracto());

                    /////////REESCRIBIR
                    if ($valor->getAtributo()->getAtributoAbstracto()->getId() == 9)
                    {
                      $cantidad+= $valor->getValor();
                    }
                    elseif ($valor->getAtributo()->getAtributoAbstracto()->getId() == 2)
                    {
                      $kilos+= $valor->getValor();
                    }
                    //////////////////////////////////

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
                    $valorAtr->setAcumula($valor->getAtributo()->getAcumula());
                    $entrada->addValore($valorAtr);
                }
                /////////REESCRIBIR////////////
                if ($cantidad)
                {
                   $procFanDay->setPesoPromedio(($kilos/$cantidad));
                }
                else
                {
                  $procFanDay->setPesoPromedio(0);
                }
                ///////////////////////////
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

                try{
                    //1º: Recupera del proceso origen, el ArticuloAtributoConcepto para el TipoMovimiento-> Salida Stock
                    $artAtrConOrigen = $this->getArticuloAtributoConceptoForMovimientoAction($articulo->getArticulo(),
                                                                                        $articulo->getConcepto()->getConcepto(),
                                                                                        SalidaStock::getInstance(),
                                                                                        $procesoOrigen,
                                                                                        $em);
                }
                catch (\Exception $e){throw $e;}
                //busca en la lista de valores de atributos del movimiento si existe el valor correspondiente al AtributoAbstracto que maneja el stock del proceso
                $valorAtributo = $movimiento->getValorWhitAtribute($articuloManejaStock->getAtributo());
                if (!$valorAtributo)
                {
                  throw new \Exception("No se encuentra el atributo en la lista de valores del movimiento!!");
                }

                $repositoryMovimiento = $em->getRepository(MovimientoStock::class);
                $stockArticulo = $proceso->getStockArticulo($faena, $articulo->getArticulo(), $articuloManejaStock->getAtributo());

                //$repositoryMovimiento->getStockDeArticulo($proceso, $articuloManejaStock->getArticulo(), $articuloManejaStock->getAtributo());
                if (is_null($stockArticulo))
                {
                  throw new \Exception("No se pudo calcular el stock del articulo ".$articuloManejaStock->getArticulo().", en el proceso ".$proceso."!!");
                }
                elseif($stockArticulo < $valorAtributo->getValor())
                {
                  throw new \Exception("El stock del articulo ".$articuloManejaStock->getArticulo()." es insuficiente!!");
                }
                elseif($valorAtributo->getValor() == 0)
                {
                  if (!$valorAtributo->getAtributo()->getAdmiteCero())
                  {
                    throw new \Exception("La cantidad a transferir debe ser mayor a 0!!");
                  }
                }

                $salida = new SalidaStock();
                $salida->setFaenaDiaria($faena);
                $salida->setProcesoFnDay($proceso);
                $salida->setArtProcFaena($artAtrConOrigen);  

                foreach ($movimiento->getValores() as $valor) 
                {
                    $valorAtr = new ValorNumerico();
                    $valorAtr->setAtributoAbstracto($valor->getAtributo()->getAtributoAbstracto());

                    $dataValue = $valor->getValor();
                    $valorAtr->setValor($dataValue);

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
            throw new \Exception("Debe seleccionar un proceso destino ".$articulo->getVistaEdicion());
        }
        
        $ok = true;
        if (($request) && (!$automatico))
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
           //   if ($automatico)
              //  $em->flush();
              return $proceso;
        }
        else
        {
              throw new \Exception("Error desconocido");
        }
    }


    private function registrarPasoRealizado(GrupoMovimientosAutomatico $grupo, 
                                            ProcesoFaena $proceso, 
                                            ProcesoFaenaDiaria $procesoFaena, 
                                            FaenaDiaria $faena,
                                            $em)
    {
        //todos los movimientos son automaticos, cuando se crea un paso de un prceso siempre apunta a un grupo de movimientos automaticos
        $paso = $em->getRepository(PasoProceso::class)->findPasoProceso($proceso, $grupo);
        if ($paso)
        {
            $pasoRealizado = $em->getRepository(PasoProcesoRealizado::class)->findPasoProcesoRealizado($procesoFaena, $faena, $paso);
            if ($pasoRealizado)
            {
                $pasoRealizado->setFechaAccion(new \DateTime());
            }
            else
            {
                  $pasoRealizado = new PasoProcesoRealizado();
                  $pasoRealizado->setFechaAccion(new \DateTime());
                  $pasoRealizado->setProcesoFaenaDiaria($procesoFaena);
                  $pasoRealizado->setFaenaDiaria($faena);
                  $pasoRealizado->setPaso($paso);
                  $em->persist($pasoRealizado);
            }
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
        $grupo = $em->getRepository(GrupoMovimientosAutomatico::class)->getMovimientoWithAtributo($proceso->getProcesoFaena(), $articulo);
        if($type == 4) //TransformarStock
        {
            try
            {
              $proceso = $this->procesarTransformarStock($proceso, $articulo, $concepto, $faena, $em, $request);

              $movimiento = $articulo->getDesencadenado(); //De existir levanta cual es el movimiento que se debe realizar seguidamente

              if ($movimiento)
              {
                  $auto = $movimiento->getArticuloAtributoConcepto();

                  $instance = $auto->getConcepto()->getTipoMovimiento()->getInstancia();
                  if ($instance == 4)
                  {
                    $proceso = $this->procesarTransformarStock($proceso, $auto, $auto->getConcepto(), $faena, $em);
                  }
                  elseif($instance == 5)
                  {
                    $var = (bool)true;
                    $proceso = $this->procesarTransferirStock($proceso, $auto, $auto->getConcepto(), $faena, $em, $movimiento, null, true);
                  }
                  elseif (in_array($instance, [2,3])) 
                  {
                    $proceso = $this->procesarEntradaSalidaStock($proceso, $auto, $auto->getConcepto(), $faena, $instance, $em);               
                  }
              }
              $proceso->setUltimoMovimiento(new \DateTime());
              $this->registrarPasoRealizado($grupo, $proceso->getProcesoFaena(), $proceso, $faena, $em);
              $em->flush();
            }
            catch(\Exception $e)
            {
                $this->addFlash('errorLoad', $e->getMessage());
                return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fanday]);
            }
        }
        elseif($type == 5)  //comienza proceso Transferencia de stockk
        {
            try
            {
              $auto = new MovimientoAutomatico();
              $proceso = $this->procesarTransferirStock($proceso, $articulo, $concepto, $faena, $em, $auto, $request, false);
              $proceso->setUltimoMovimiento(new \DateTime());
              $this->registrarPasoRealizado($grupo, $proceso->getProcesoFaena(), $proceso, $faena, $em);

              $em->flush();
            }
            catch(\Exception $e)
            {
                $this->addFlash('errorLoad', $e->getMessage()." -ERROR");
                return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fanday]);
            }

            return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fanday]);
        }

        if (in_array($type, [2, 3, '2', '3']))
        {
            try
            {
              $this->procesarEntradaSalidaStock($proceso, $articulo, $concepto, $faena, $type, $em, $request);
              $proceso->setUltimoMovimiento(new \DateTime());
              $this->registrarPasoRealizado($grupo, $proceso->getProcesoFaena(), $proceso, $faena, $em);
              $em->flush();
            }
            catch(\Exception $e)
            {
                $this->addFlash('errorLoad', $e->getMessage()." -ERROR");
                return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fanday]);
            } 
        }

          return $this->redirectToRoute('bd_adm_proc_fan_day', ['proc' => $proc, 'fd' => $fanday]);
        
    }

    public static function getArticuloAtributoConceptoForMovimientoAction(\GestionFaenaBundle\Entity\gestionBD\Articulo $articulo,
                                                              \GestionFaenaBundle\Entity\faena\ConceptoMovimiento $concepto,
                                                              $instanceOfTipoMovimiento,
                                                              \GestionFaenaBundle\Entity\ProcesoFaena $proceso,
                                                              $em,
                                                               $response = false) 
    //para los parametro dados, devuelve un ArticuloAtributoConcepto si existe, sino crea uno
    {
        //  $logger = $this->get('logger');
          try{
              //busca si existe el articulo atributo concepto para 
              $repositoryAAC = $em->getRepository(ArticuloAtributoConcepto::class);
              $artAtrCon = $repositoryAAC->findArticuloAtributoConcepto($articulo, $concepto, $instanceOfTipoMovimiento, $proceso);
              
          /*    if (count($artAtrCon))
              {
                $artAtrCon = $artAtrCon[0];
                $logger->info('CANTIDAD '.$artAtrCon->getId());
              }*
             // $logger->info('CANTIDAD '.$artAtrCon->getId());
           /*   foreach ($artAtrCon as $a)
              {
                $logger->info('IDEDETE '.$a->getId());

              }*/
             // return;
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
              if ($response)
              {
                  return new Response($artAtrCon);
              }
              return $artAtrCon;
            }
            catch (\Exception $e) { 


                //  $logger->info('ESTO ES UN ERRORETE '.$e->getMessage());

                  throw new \Exception("Instancia: $instanceOfTipoMovimiento - Articulo: ".$articulo->getId()." - Concepto: ".$concepto->getId()."  Proceso: ".$proceso->getId()); }
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
                                  'articulo' => $art->getArticuloOrigenTransformacion(),
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
                  return $this->createForm(TransferirStockType::class, 
                                           $movimiento, 
                                           ['faena' => $fanday,
                                            'proceso' => $proc,
                                            'articulo' => $art->getArticulo(),
                                            'manager' => $this->getDoctrine()->getManager(),
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


    private function getFormDeleteMovimiento($mov, $trx, $faena, $type = null)
    {
        $params = array('mov' => $mov, 'trx' => $trx, 'faena' => $faena);
        if ($type)
        {
          $params['typ'] = $type;
        }
        $form = $this->createFormBuilder()
                      ->add('delete', SubmitType::class, ['label' => 'Eliminar']) 
                      ->setAction($this->generateUrl('bd_adm_mov_st_delete', $params))     
                      ->setMethod('DELETE')   
                      ->getForm();
        return $form;
    }
    /**
     *@Route("/gst/{mov}/{trx}/{faena}/{typ}", name="bd_adm_mov_st_delete", methods={"DELETE", "POST"})
     *@Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function eliminarMovimientoStockAction(Request $request, $mov, $trx, $faena, $typ = null)
    {
        $em = $this->getDoctrine()->getManager();
        $movimiento = $em->find(MovimientoStock::class, $mov);
        $id = $movimiento->getProcesoFnDay()->getId();
        if ($trx)
        {
            $transferencia = $em->find(MovimientoCompuesto::class, $trx);
            $transferencia->getMovimientoOrigen()->setEliminado(true);
            $transferencia->getMovimientoDestino()->setEliminado(true);
            $transferencia->setEliminado(true);
        }
        else
        {
            $movimiento->setEliminado(true);
        }
        $em->flush();
        $params = ['proc' => $id, 'fd' => $faena];
        if ($typ)
        {
          $params['typ'] = $typ;
        }

        if ($request->isXmlHttpRequest()) 
        {  
            return new JsonResponse(['status' => true, 'message' => 'OKA']);
        } 
        return $this->redirectToRoute('bd_adm_proc_fan_day', $params);
    }

////////////////remove from AJAX ///////////////////////////////
    /**
     * @Route("/gstObsolet/{mov}/{trx}/{faena}/{typ}", name="bd_adm_mov_st_delete_obsolet", methods={"POST", "DELETE"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function eliminarMovimientoStockAjaxAction($mov, $trx, $faena, $typ = null)
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
        return new JsonResponse(['status' => true]);
      /*  $params = ['proc' => $id, 'fd' => $faena];
        if ($typ)
        {
          $params['typ'] = $typ;
        }
        return $this->redirectToRoute('bd_adm_proc_fan_day', $params);*/
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
