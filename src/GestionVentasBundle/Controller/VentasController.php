<?php

namespace GestionVentasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GestionFaenaBundle\Entity\gestionBD\EntidadExterna;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotNull;
use GestionFaenaBundle\Entity\faena\ComprobanteVenta;
use GestionFaenaBundle\Entity\gestionBD\Articulo;
use GestionFaenaBundle\Entity\faena\TipoVenta;
use GestionFaenaBundle\Entity\faena\ItemCarga;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use GestionFaenaBundle\Entity\gestionBD\RubroEntidad;
use GestionFaenaBundle\Form\gestionBD\RubroEntidadType;
use GestionFaenaBundle\Entity\FaenaDiaria;
use GestionFaenaBundle\Entity\ProcesoFaena;
use GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria;
use GestionVentasBundle\Entity\options\EntidadExternaConcepto;
use GestionFaenaBundle\Entity\faena\SalidaStock;
use GestionFaenaBundle\Controller\GestionFaenaController;
use GestionFaenaBundle\Entity\faena\ValorNumerico;
use GestionFaenaBundle\Entity\faena\OrdenCarga;
use Psr\Log\LoggerInterface;

/**
 * @Route("/ventas")
 */

class VentasController extends Controller
{

    ////////////////////////ALTA ARTICULO ATRIBUTO CONCEPTO
    /**
     * @Route("/config/setgpo", name="bd_set_grupo_entidad")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function setGrupoEntidad()
    {
        $em = $this->getDoctrine()->getManager();
        $entes = $em->getRepository(EntidadExterna::class)->getClientes();
        $forms = [];
        foreach ($entes as $e)
        {
            $forms[$e->getId()] = $this->getFormChangeRubro($e)->createView();
        }
        return $this->render('@GestionVentas/bd/asignarRubrosAEntes.html.twig', ['forms' => $forms, 'entes' => $entes]);
    }

    /**
     * @Route("/config/chgpo/{id}", name="bd_change_grupo_entidad", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function cambiarGrupoEntidad($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entidad = $em->find(EntidadExterna::class, $id);
        $form = $this->getFormChangeRubro($entidad);
        $form->handleRequest($request);
        $data = $form->getData();
        $entidad->setRubro($data['grupo']);
        $em->flush();
        return new JsonResponse(['error' => false]);
    }

    private function getFormChangeRubro(EntidadExterna $e)
    {
        $form =$this->createFormBuilder()
                    ->add('grupo', 
                          EntityType::class, [
                          'class' => RubroEntidad::class,    
                          'data' => $e->getRubro(),
                          'required' => false,                      
                          'query_builder' => function (EntityRepository $er) {
                                                                                return $er->createQueryBuilder('e')
                                                                                          ->where('e.activo = :activo')
                                                                                          ->setParameter('activo', true)
                                                                                          ->orderBy('e.rubro');
                                                                             },
                    ])
                    ->setAction($this->generateUrl('bd_change_grupo_entidad', [ 'id' => $e->getId()]))  
                    ->setMethod('POST')               
                    ->getForm();
        return $form;
    }


    /**
     * @Route("/config/addgpo", name="bd_ventas_nuevo_grupo_ventas")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addGrupoVenta()
    {
        $grupo = new RubroEntidad();
        $form = $this->getFormGrupoVenta($grupo);
        $rubros = $this->getDoctrine()->getManager()->getRepository(RubroEntidad::class)->findAll();
        return $this->render('@GestionVentas/bd/nuevoGrupo.html.twig', array('rubros' => $rubros, 'form' => $form->createView()));
    }

    private function getFormGrupoVenta($grupo)
    {
        return $this->createForm(RubroEntidadType::class, $grupo, ['action' => $this->generateUrl('bd_ventas_nuevo_grupo_ventas_procesar'),'method' => 'POST']);
    }

    /**
     * @Route("/config/addartatconproc", name="bd_ventas_nuevo_grupo_ventas_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormGrupoVenta(Request $request)
    {
        $grupo = new RubroEntidad();
        $form = $this->getFormGrupoVenta($grupo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grupo);
            $entityManager->flush();
            return $this->redirectToRoute('bd_ventas_nuevo_grupo_ventas');
        }
        $rubros = $this->getDoctrine()->getManager()->getRepository(RubroEntidad::class)->findAll();
        return $this->render('@GestionVentas/bd/nuevoGrupo.html.twig', array('rubros' => $rubros, 'form' => $form->createView()));
    }
    /////////////////FIN///////////////////////////////////
    /**
 	 * @Route("/generar", name="vtas_generate")
     */
    public function generarVentaAction(Request $request)
    {
    	$parameters = [];
    	$data = $request->request->get('form');
    	$fecha = null;
    	if ($data['fecha'])
    	{
    		$fecha = \DateTime::createFromFormat('Ymd', $data['fecha']);
    		$parameters['fecha'] = $fecha;
    	}

    	$form = $this->getFormIngresarVenta($fecha);
    	$parameters['form'] = $form->createView();
        return $this->render('@GestionVentas/ventas/nuevaVenta.html.twig', $parameters);
    }

    private function getFormIngresarVenta($fecha = null)
    {
    	$form =$this->createFormBuilder()
    				->add('fecha', 
    					  DateType::class, 
    					  ['widget' => 'single_text',
    					   'required' => true,
    					   'data' => $fecha,
                           'constraints' => [
                                                    new NotNull(['message' => 'Debe seleccionar una fecha!!!']),
                                             ]
                           ])
    				->add('comentario', TextareaType::class)
                    ->add('cliente', 
                          EntityType::class, [
                          'class' => EntidadExterna::class,                          
                          'query_builder' => function (EntityRepository $er) {
																		        return $er->createQueryBuilder('e')
																		            	  ->where('e.activa = :activa')
																		            	  ->andWhere('e.cliente = :cliente')
																						  ->setParameter('activa', true)
																						  ->setParameter('cliente', true)
																						  ->orderBy('e.valor');
																		     },
                    ])
                    ->add('siguiente', SubmitType::class, ['label' => 'Siguiente >>'])    
                    ->setAction($this->generateUrl('vtas_generate_procesar'))  
                    ->setMethod('POST')               
                    ->getForm();
        return $form;
    }

    /**
 	 * @Route("/generarprc", name="vtas_generate_procesar", methods="POST")
     */
    public function procesarGenerarVentaAction(Request $request)
    {
    	$form = $this->getFormIngresarVenta();
    	$form->handleRequest($request);
    	if ($form->isValid())
    	{
    		$data = $form->getData();
    		$em = $this->getDoctrine()->getManager();

    		$repository = $em->getRepository(ComprobanteVenta::class);
    		$comprobante = $repository->getComprobanteConEntidadYFecha($data['fecha'], $data['cliente']);
    		if ($comprobante)
    		{
    			$this->addFlash(
                          'error',
                          'Existe un comprobante para '.$data['cliente'].' en la fecha seleccionada!'
                      );

    			return $this->render('@GestionVentas/ventas/nuevaVenta.html.twig', ['form' => $form->createView(), 'fecha' => $data['fecha']]); 
    		}

    		$compVenta = new ComprobanteVenta();
    		$compVenta->setUserAlta($this->getUser());
    		$compVenta->setComentario($data['comentario']);
    		$compVenta->setFecha($data['fecha']);
    		$compVenta->setEntidad($data['cliente']);

    		
    		$em->persist($compVenta);
    		$em->flush();
    		return $this->redirectToRoute('vtas_agregar_articulos',['id' => $compVenta->getId(), 'request' => $request]);
    	}
        return $this->render('@GestionVentas/ventas/nuevaVenta.html.twig', ['form' => $form->createView()]);
    }

    /**
 	 * @Route("/addart/{id}", name="vtas_agregar_articulos")
     */
    public function agregarArticulosAComprobanteVentaAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$comprobante = $em->find(ComprobanteVenta::class, $id);

    	$repoArticulos = $em->getRepository(Articulo::class);

    	$articulos = $repoArticulos->getListaArticulosConCategoria(); //Recupera todos los articulos que se pueden comercializar

    	$repoTipos = $em->getRepository(TipoVenta::class);
    	$tiposItem = $repoTipos->findAll(); //Recupera todos los tipos de Item Rayado o No

    	$formVentas = [];

    	$listaArticulos = [];

    	foreach ($articulos as $art)
    	{
    		$listaArticulos[$art->getId()] = $art;

    		$formVentas[$art->getId()] = [];

    		foreach ($tiposItem as $tpo)
    		{
    			$item = $comprobante->getItemConTipoYArticulo($tpo, $art);

    			$formVentas[$art->getId()][$tpo->getId()] = $this->getFormAltaItem($comprobante, $art, $tpo, $item)->createView();
    		}    		
    	}

    	$form = $this->createFormBuilder()
    				 ->add('back', SubmitType::class, ['label' => 'Guardar y Volver'])    
                     ->setAction($this->generateUrl('vtas_volver_add_items'))  
                     ->add('fecha', HiddenType::class, ['data' => $comprobante->getFecha()->format('Ymd')])
                     ->setMethod('POST')               
                     ->getForm();

        return $this->render('@GestionVentas/ventas/agregarItems.html.twig', ['comprobante' => $comprobante,
    																		  'ventas' => $formVentas,
    																		  'articulos' => $listaArticulos,
    																		  'tipos' => $tiposItem,
    																		  'back' => $form->createView()]);
    }

    /**
 	 * @Route("/back", name="vtas_volver_add_items", methods={"POST"})
     */
    public function backFromAddItems(Request $request)
    {
    	return $this->redirectToRoute('vtas_generate', ['request' => $request], 307);
    }

    private function getFormAltaItem(ComprobanteVenta $comp, Articulo $articulo, TipoVenta $tipo, ItemCarga $it = null)
    {
    	 $data = null;
    	 if ($it)
    	 {
    	 	$data = $it->getCantidad();
    	 }

    	 $disabled = false;
    	 if ($comp->getConfirmado())
    	 {
    	 	$disabled = true;
    	 }
    	 return $this->createFormBuilder()
					 ->add('cantidad', 
					 	   NumberType::class,
					 	   ['data' => $data,
					 	   	'disabled' => $disabled
					 		])
					 ->setAction($this->generateUrl('vtas_agregar_articulo_procesar', 
					 								[
					 									'cmp' => $comp->getId(),
					 									'tpo' => $tipo->getId(),
					 									'art' => $articulo->getId()
					 								 ]))
					 ->getForm();
    }

    /**
 	 * @Route("/addartproc/{cmp}/{tpo}/{art}", name="vtas_agregar_articulo_procesar")
     */
    public function procesarAgregarArticuloAComprobanteVentaAction(ComprobanteVenta $cmp,
    															   TipoVenta $tpo,
    															   Articulo $art,
    															   Request $request)
    {
    	if ($cmp->getConfirmado())
    	{
    		return new JsonResponse(['error' => true, 'message' => 'El comprobante ya ha sido impreso!!']);
    	}

    	try
    	{
	    	$itemCarga = $cmp->getItemConTipoYArticulo($tpo, $art);
	    	$persistir = false;
	    	$em = $this->getDoctrine()->getManager();
	    	if (!$itemCarga)
	    	{
	    		$itemCarga = new ItemCarga();
	    		$persistir = true;
	    	}

	    	$form = $this->getFormAltaItem($cmp, $art, $tpo, $itemCarga);
	    	$form->handleRequest($request);
	    	$data = $form->getData();  	    	

	    	if ((!$persistir) && (!$data['cantidad'])) //significa que el item tiene valor 0, por lo cual el mismo debe ser eliminado
	    	{
	    		$em->remove($itemCarga);
	    	}
	    	else
	    	{
	    		$itemCarga->setCantidad($data['cantidad']);
	    	}

	    	if ($persistir)
	    	{
		    	$itemCarga->setComprobante($cmp);
		    	$itemCarga->setArticulo($art);
		    	$itemCarga->setTipoVenta($tpo);
		    	$em->persist($itemCarga);
	    	}
	    	
	    	$em->flush();
	    	return new JsonResponse(['error' => false]);
	    }
	    catch (\Exception $e){ return new JsonResponse(['error' => true, 'message' => $e->getMessage()]); }

    }

    /**
 	 * @Route("/detvtaday", name="vtas_detalle_ventas_fecha")
     */
    public function detalleVentasEnFecha(Request $request)
    {
    	$form = $this->getFormIngresarVenta();
    	$form->handleRequest($request);
    	$data = $form->getData();
    	$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository(ComprobanteVenta::class);
    	
    	$tiposVenta = $em->getRepository(TipoVenta::class)->findAll();

    	$articulos = $em->getRepository(Articulo::class)->getListaArticulosConCategoria();

    	$comprobantes = $repository->getComprobantesVenta($data['fecha']);

    	$body = [];

    	foreach ($comprobantes as $comp)
    	{
    		$body[$comp->getId()] = [];
    		foreach ($articulos as $art)
    		{
    			$body[$comp->getId()][$art->getId()] = [];

    			foreach ($tiposVenta as $tpo)
    			{
    				$body[$comp->getId()][$art->getId()][$tpo->getId()] = null;
    			}
    		}

    		foreach ($comp->getItems() as $it)
    		{
    			$body[$comp->getId()][$it->getArticulo()->getId()][$it->getTipoVenta()->getId()] = $it->getCantidad();
    		}
    	}

    	return $this->render('@GestionVentas/ventas/listaVentasDiarias.html.twig', 
    						 ['fecha' => $data['fecha'],
    						  'comprobantes' => $comprobantes,
    						  'articulos' => $articulos,
    						  'tipos' => $tiposVenta,
    						  'data' => $body]);
    }

    /**
 	 * @Route("/delete/{id}", name="vtas_delete_comprobante_venta")
     */
    public function deleteComprobanteVenta($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$comprobante = $em->find(ComprobanteVenta::class, $id);

    	if ((!$comprobante) || ($comprobante->getEliminado())) //no se encuentra el comprobante o e mismo ha sido eliminado
    	{
    		return new JsonResponse(['error' => true, 'message' => 'No se encuentra el comprobante']);
    	}

    	if ($comprobante->getConfirmado())
    	{
    		return new JsonResponse(['error' => true, 'message' => 'El comprobante ya se ha impreso, no se puede eliminar']);
    	}

    	try
    	{
	    	$comprobante->setEliminado(true);
	    	$comprobante->setUserBaja($this->getUser());
	    	$em->flush();
	    	return new JsonResponse(['error' => false]);
    	}
    	catch (\Exception $e){ return new JsonResponse(['error' => true, 'message' => $e->getMessage()]); }
    }

    /**
 	 * @Route("/finalice/{id}", name="vtas_finalizar_comprobante_venta")
     */
    public function finalizarComprobanteVenta($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$comprobante = $em->find(ComprobanteVenta::class, $id);

    	if ((!$comprobante) || ($comprobante->getEliminado())) //no se encuentra el comprobante o e mismo ha sido eliminado
    	{
    		return new JsonResponse(['error' => true, 'message' => 'No se encuentra el comprobante']);
    	}

    	if ($comprobante->getConfirmado())
    	{
    		return new JsonResponse(['error' => true, 'message' => 'El comprobante ya se encuentra confirmado!']);
    	}

    	try
    	{
    		if (!$comprobante->getItems()->count())
    		{
    			return new JsonResponse(['error' => true, 'message' => 'El comprobante no tiene cargado ningun articulo!']);
    		}
	    	$comprobante->setFinalizado(true);
            $comprobante->setFechaFinalizacion(new \DateTime());
	    	$comprobante->setUserFinalizacion($this->getUser());
	    	$em->flush();
	    	return new JsonResponse(['error' => false]);
    	}
    	catch (\Exception $e){ return new JsonResponse(['error' => true, 'message' => $e->getMessage()]); }
    }

    /**
 	 * @Route("/lastvtas", name="vtas_ultimos_comprobantes_venta")
     */
    public function getUltimosComprobantesVenta()
    {
		$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository(ComprobanteVenta::class);
    	$comprobantes = $repository->getUltimosComprobantesVenta();
    	$exists = (count($comprobantes)?true:false);
    	return new JsonResponse(['exists' => $exists, 'cant' => count($comprobantes)]);
    }

    /**
     * @Route("/incvtas", name="vtas_incorporar_ventas_a_faena", methods={"POST", "GET"})
     */
    public function incorporarVentasAFaena(Request $request)
    {        

        $du = $request->query->get('du');
        $fecha = null;
        if($du)
        {
            $fecha = new \DateTime();
            $fecha->setTimestamp($du);
        }


        $form = $this->createFormBuilder()
                     ->add('fecha', 
                          DateType::class, 
                          ['widget' => 'single_text',
                           'required' => true,
                           'data' => $fecha,
                           'constraints' => [
                                                    new NotNull(['message' => 'Debe seleccionar una fecha!!!']),
                                             ]
                           ])
                      ->add('cargar', SubmitType::class, ['label' => 'Cargar'])
                      ->getForm();
        if ($request->isMethod('POST') || ($fecha))
        {
            $em = $this->getDoctrine()->getManager();
            $form->handleRequest($request);
            if ($form->isValid() || $fecha)
            {
                $data = $form->getData();
                $fechaConsulta = ($fecha?$fecha:$data['fecha']);
                $repository = $em->getRepository(ComprobanteVenta::class);
                $comprobantes = $repository->getComprobantesVentaFinalizados($fechaConsulta);
                $options = ['form' => $form->createView(), 'comprobantes' => $comprobantes];
                return $this->render('@GestionVentas/ventas/incorporarVentas.html.twig', $options); 
            }
        }

        return $this->render('@GestionVentas/ventas/incorporarVentas.html.twig', ['form' => $form->createView()]); 
    }

    /**
     * @Route("/print/{cmp}", name="vtas_imprimir_comprobante")
     */
    public function imrpimirComprobante($cmp)
    {
        $em = $this->getDoctrine()->getManager();

        $comprobante = $em->find(ComprobanteVenta::class, $cmp);

        //obtiene la faena diaria de la fecha del comprobante
        $faenaDiaria = $em->getRepository(FaenaDiaria::class)->getFaenaConFecha($comprobante->getFecha());

        if (!$comprobante->getItemOficial())
        {
            $this->addFlash(
                  'error',
                  'No es posible generar el remito para el comprobante '.str_pad($comprobante->getNumero(), 6, "0", STR_PAD_LEFT).'!!'
              );
            return $this->redirectToRoute('vtas_incorporar_ventas_a_faena', ['du' => $comprobante->getFecha()->getTimestamp()]);
        }

        if (!$faenaDiaria)
        {
            $this->addFlash(
                  'error',
                  'No existe una faena iniciada para el '.$comprobante->getFecha()->format('d/m/Y'.'!')
              );
            return $this->redirectToRoute('vtas_incorporar_ventas_a_faena', ['du' => $comprobante->getFecha()->getTimestamp()]);
        }



        //recupera el ProcesoFaena que admite se generen ventas (Gestion Camaras)
        $procesoFaena = $em->getRepository(ProcesoFaena::class)->getProcesoVenta();

        //Recupera el ProcesoFaenaDiaria correspondiente al ProcesoFaena recuperado anteriormente
        $procesoFaenaDiaria = $em->getRepository(ProcesoFaenaDiaria::class)->getProcesoFaenaDiariaWhitProcess($procesoFaena);

        $entidadConcepto = $em->getRepository(EntidadExternaConcepto::class)->getEntidadOfClass(get_class($comprobante->getEntidad()), $procesoFaena);

        $conceptoMovimiento = $entidadConcepto->getConcepto();

        //Buscar si existe una orden de carga generada para la entidad del comprobante
        $ordenCarga = $em->getRepository(OrdenCarga::class)->findOrdenCargaEntidad($comprobante->getEntidad(), $faenaDiaria, $comprobante);

     /*   if (!$ordenCarga)
            return new Response('OK '.$comprobante->getEntidad()->getRubro()->getId());*/

        $ingresar = true;
        if (!$ordenCarga) 
        {
            //La orden de carga no existe, debe generar una
            $ordenCarga = new OrdenCarga();
            $ordenCarga->setFecha($comprobante->getFecha());
            //agrega el comprobante de venta a la orden
            $ordenCarga->addComprobante($comprobante);
            //agrega la entidad como destinatario
            $ordenCarga->addEntidade($comprobante->getEntidad());
            //de existir agrega el rubro, esto se utiliza cuando varias entidades se agrupan en una misma orden
            $ordenCarga->setRubro($comprobante->getEntidad()->getRubro());

            $ordenCarga->setFaenaDiaria($faenaDiaria);
            $comprobante->setConfirmado(true);
            $em->persist($ordenCarga);
        }
        else
        {
            if (!$ordenCarga->existeComprobanteCargado($comprobante))
            {
                $comprobante->setConfirmado(true);
                $comprobante->setUserConfirm($this->getUser());
                $ordenCarga->addComprobante($comprobante);
                $ordenCarga->addEntidade($comprobante->getEntidad());
            }
            else
            {
                $ingresar = false; //No debe generar ningun ingreso ya que la orden ya se ha procesado
            }
        }

        foreach ($comprobante->getItems() as $it)
        {

            if ($ingresar)
            {
                $artAtrCon = GestionFaenaController::getArticuloAtributoConceptoForMovimientoAction( 
                                                                                                   $it->getArticulo(),
                                                                                                    $conceptoMovimiento,
                                                                                                    SalidaStock::getInstance(),
                                                                                                   $procesoFaena,
                                                                                                    $em);
                $salida = new SalidaStock();
                $salida->setFaenaDiaria($faenaDiaria);
                $salida->setProcesoFnDay($procesoFaenaDiaria);
                $salida->setArtProcFaena($artAtrCon);

                $valorAtr = new ValorNumerico();
                $valorAtr->setAtributoAbstracto($procesoFaena->getAtributoVenta());
                $valorAtr->setValor($it->getCantidad());
                $valorAtr->setUnidadMedida($procesoFaena->getUnidadMedidaventa());
                $valorAtr->setMostrar(true);
                $valorAtr->setDecimales(0);
                $valorAtr->setAcumula(true);
                $salida->addValore($valorAtr);
                $it->setItemMovimientoStock($valorAtr);
                $em->persist($salida);
            }
        }
        if ($ingresar)
        {
            $comprobante->setOrdenCarga($ordenCarga);
            $comprobante->setArtProcFaena($artAtrCon);
            $comprobante->setFaenaDiaria($faenaDiaria);
            $comprobante->setProcesoFnDay($procesoFaenaDiaria);
        }
        $em->flush();

        $items = $em->getRepository(ItemCarga::class)->itemsAImprimir($comprobante, 1); //recupera todos los items oficiales del comprobante

        $pdf = $this->get('app.pdf');
        $pdf->setLogo($this->get('kernel')->getRootDir() . '/../web/resources/img/logo2.jpg');
        $pdf->setData($comprobante->getEntidad(), $comprobante->getFecha(), str_pad($comprobante->getNumero(), 10, "0", STR_PAD_LEFT), 0, '');
        $pdf->AliasNbPages();

        
        $pdf->SetAutoPageBreak(true,33);  
        $pdf->AddPage('L', 'legal'); 
        $pdf = $this->paintData($pdf, $items);
      //  $pdf->AddPage('P', 'A4'); 
        return new Response($pdf->Output(), 200, array('Content-Type' => 'application/pdf'));  
    }

    private function paintData($pdf, $items, $oc = 0)
    {
        $last = 0;
        $unidades = $kilos = 0;
        $xdesp = 173;
        $x = $pdf->getX()-4;
        
        $y = $pdf->getY()+5;
        foreach ($items as $it)
        {
            $item = null;
            $cantAux = null;
            if (is_array($it))
            {
                $item = $it[0];
                $cantAux = $it['cantidad'];
            }
            else
            {
                $item = $it;
                $cantAux = $it->getCantidad();
            }

            $art = $item->getArticulo();
            $gpo = $art->getCategoria()->getGrupo();
            $pdf->setX($x);
            if (($last !== $gpo) && ($last))
            {
                $pdf->cell(40, 7, '', 0,0,'L');
                $pdf->cell(70, 7, 'TOTAL GRUPO: '.$last.' ->', 0,0,'L');
                $pdf->cell(30, 7, $unidades, 0,0,'L');
                
                
                if (!$oc)
                {
                    $pdf->cell(30, 7, $kilos.' KILOS', 0,0,'R');
                    $pdf->setX($x+$xdesp);
                    $pdf->cell(40, 7, '', 0,0,'L');
                    $pdf->cell(70, 7, 'TOTAL GRUPO: '.$last.' ->', 0,0,'L');
                    $pdf->cell(30, 7, $unidades, 0,0,'L');
                    $pdf->cell(30, 7, $kilos.' KILOS', 0,1,'R');
                }
                else
                {
                    $pdf->cell(30, 7, $kilos.' KILOS', 0,1,'R');
                }
                $pdf->setX($x);

                $unidades = $kilos = 0;
            }
            
            $pdf->cell(25, 7, $item->getArticulo()->getCodigoInterno(), 0,0,'L');
            $pdf->cell(115, 7, strtoupper($item->getArticulo()), 0,0,'L');            

            if (!$oc)
            {
                $pdf->cell(30, 7, $cantAux, 0, 0,'R');
                $pdf->setX($x+$xdesp);
                $pdf->cell(25, 7, $item->getArticulo()->getCodigoInterno(), 0,0,'L');
                $pdf->cell(115, 7, strtoupper($it->getArticulo()), 0,0,'L');
                $pdf->cell(30, 7, $cantAux, 0, 1,'R');
            }
            else
            {
                $pdf->cell(30, 7, $cantAux, 0, 1,'R');
            }
            $pdf->setX($x);

            $unidades+= $cantAux;
            $kilos+= ($cantAux * $item->getArticulo()->getPresentacionKg());
            $last = $gpo;
        }
        $pdf->setX($x);
        $pdf->cell(40, 7, '', 0,0,'L');
        $pdf->cell(70, 7, 'TOTAL GRUPO: '.$last.' ->', 0,0,'L');
        $pdf->cell(30, 7, $unidades, 0,0,'L');
        $pdf->cell(30, 7, $kilos.' KILOS', 0,0,'R');
        $pdf->setX($x+$xdesp);
        $pdf->cell(40, 7, '', 0,0,'L');
        $pdf->cell(70, 7, 'TOTAL GRUPO: '.$last.' ->', 0,0,'L');
        $pdf->cell(30, 7, $unidades, 0,0,'L');
        $pdf->cell(30, 7, $kilos.' KILOS', 0,0,'R');
        return $pdf;
    }

    /**
     * @Route("/printoc/{oc}", name="vtas_imprimir_orden_carga")
     */
    public function imrpimirOrdenCarga($oc)
    {
        $em = $this->getDoctrine()->getManager();

        $ordenCarga = $em->find(OrdenCarga::class, $oc);

        $items = $em->getRepository(ItemCarga::class)->getItemsOrdenCarga($ordenCarga); //recupera todos los items oficiales del comprobante

        //recupera la cantidad de comprobantes generados para las entidades con rubr igual al rubro de la Orden de Carga, sino coincide, quiere ecir que existen comprobantes que aun no han sido ingresados
        $comprobantes = $em->getRepository(ComprobanteVenta::class)
                           ->getComprobantesVentaOfRubroEntidad($ordenCarga->getFecha(), $ordenCarga->getRubro());

        if (count($comprobantes) > $ordenCarga->getComprobantes()->count())
        {
            $this->addFlash(
                  'error',
                  'Existen comprobantes de venta que aun no han sido incorporados'
              );
            return $this->redirectToRoute('vtas_incorporar_ventas_a_faena', ['du' => $ordenCarga->getFecha()->getTimestamp()]);
        }

        $pdf = $this->get('app.pdf');

        $entidad = ($ordenCarga->getRubro()?$ordenCarga->getRubro():$ordenCarga->getComprobantes()->first()->getEntidad());

        $numero = "";
        foreach ($ordenCarga->getComprobantes() as $cmp)
        {
            if ($numero)
            {
                $numero.=" - ";
            }

            $numero.= str_pad($cmp->getNumero(), 10, "0", STR_PAD_LEFT);
        }

        $trx = $ordenCarga->getTitularTransporte();
        $pdf->setData($entidad, $ordenCarga->getFecha(), $numero, 1, $trx);



        $pdf->setLogo($this->get('kernel')->getRootDir() . '/../web/resources/img/logo2.jpg');

        $pdf->AliasNbPages();

        
        $pdf->SetAutoPageBreak(true,33);  
        $pdf->AddPage('P', 'legal'); 
        $pdf = $this->paintData($pdf, $items, 1);
      //  $pdf->AddPage('P', 'A4'); 
        return new Response($pdf->Output(), 200, array('Content-Type' => 'application/pdf'));  
    }

    /**
     * @Route("/viewdeta/{cmp}/{tpo}", name="vtas_view_detalle_cmbte")
     */
    public function viewDetalleComprobante($cmp, $tpo = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(ComprobanteVenta::class);
        $comprobante = $repository->find($cmp);

        $next = $repository->proximoComprobante($comprobante);
        $back = $repository->anteriorComprobante($comprobante);

        $types = $em->getRepository(TipoVenta::class)->findAll();
        $tipos = [];
        foreach ($types as $t)
        {
            $tipos[$t->getId()] = $t->getCodigo();
        }


        $data = [];

        $articulos = [];

        $logger = $this->get('logger');
        foreach ($comprobante->getItems() as $it)
        {
            $art = $it->getArticulo();
            $articulos[$art->getId()] = $art;

            if (!array_key_exists($art->getId(), $data))
            {
                $data[$art->getId()] = [];
            }
            $data[$art->getId()][$it->getTipoVenta()->getId()] = $it->getCantidad();

            $logger->info('TIPO VENTA '.$it->getTipoVenta()->getId());
        }

        $logger->info(' '.json_encode($data));
        $logger->info(' '.json_encode($articulos));
        asort($articulos);
        $options = ['cht' => $tpo, 'comprobante' => $comprobante, 'tipos' => $tipos, 'data' => $data, 'articulos' => $articulos];
        if ($next)
        {
            $options['next'] = $next;
        }
        if ($back)
        {
            $options['back'] = $back;
        }
        if ($comprobante->getOrdenCarga())
        {
            $form = $this->getFormTransportista($comprobante->getOrdenCarga());
            $options['form'] = $form->createView();
        }        
        return $this->render('@GestionVentas/ventas/detalleComprobante.html.twig', $options);

    }

    private function getFormTransportista($ordenCarga)
    {
        $form =$this->createFormBuilder()
                    ->add('transporte', 
                          EntityType::class, [
                          'data' => $ordenCarga->getTransportista(),
                          'class' => 'GestionFaenaBundle:gestionBD\Transportista',    
                          'required' => false,  
                          'choice_label' => 'titular',                    
                          'query_builder' => function (EntityRepository $er) {
                                                                                return $er->createQueryBuilder('t')
                                                                                          ->where('t.activa = :activa')
                                                                                          ->setParameter('activa', true)
                                                                                          ->orderBy('t.valor');
                                                                             },
                    ]) 
                    ->setAction($this->generateUrl('vtas_cambiar_transportista_oc', ['oc' => $ordenCarga->getId()]))  
                    ->setMethod('POST')               
                    ->getForm();
        return $form;
    }


    /**
     * @Route("/chtransoc/{oc}", name="vtas_cambiar_transportista_oc")
     */
    public function cambiarTransportistas($oc, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(OrdenCarga::class);
        $ordenCarga = $repository->find($oc);

        $form = $this->getFormTransportista($ordenCarga);
        $form->handleRequest($request);
        $data = $form->getData();
        $transportista = $data['transporte'];
        $ordenCarga->setTransportista($transportista);
        $em->flush();

        return new JsonResponse(['status' => true, 'transp' => $transportista->getTitular()]);
    }

    /**
     * @Route("/historicovta", name="vtas_historico_ventas", methods={"POST", "GET"})
     */
    public function generateHistoricoVentas(Request $request)
    {
        $form = $this->getFormHistoricoVenta();

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();
                $comprobantes = $this->getDoctrine()
                                     ->getManager()
                                     ->getRepository(ComprobanteVenta::class)
                                     ->getComprobantesVentaFinalizados($data['desde'], $data['hasta']);
                return $this->render('@GestionVentas/ventas/historicoVentas.html.twig', ['form' => $form->createView(),'comprobantes' => $comprobantes]);
            }
        }
        return $this->render('@GestionVentas/ventas/historicoVentas.html.twig', ['form' => $form->createView()]);
    }

    private function getFormHistoricoVenta()
    {
        $form =$this->createFormBuilder()
                    ->add('desde', 
                          DateType::class, 
                          ['widget' => 'single_text',
                           'required' => true,
                           'constraints' => [
                                                    new NotNull(['message' => 'Debe seleccionar una fecha!!!']),
                                             ]
                           ])
                    ->add('hasta', 
                          DateType::class, 
                          ['widget' => 'single_text',
                           'required' => true,
                           'constraints' => [
                                                    new NotNull(['message' => 'Debe seleccionar una fecha!!!']),
                                             ]
                           ])
                    ->add('cargar', SubmitType::class, ['label' => 'Cargar'])    
                    ->setAction($this->generateUrl('vtas_historico_ventas'))  
                    ->setMethod('POST')               
                    ->getForm();
        return $form;
    }
}
