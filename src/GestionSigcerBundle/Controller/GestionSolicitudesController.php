<?php

namespace GestionSigcerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use GestionSigcerBundle\Entity\GrupoSolicitud;
use GestionSigcerBundle\Entity\TropaSolicitud;
use GestionSigcerBundle\Entity\Solicitud;
use GestionSigcerBundle\Entity\opciones\Envase;
use GestionSigcerBundle\Entity\opciones\Zona;
use GestionSigcerBundle\Entity\opciones\Producto;
use GestionSigcerBundle\Entity\opciones\Region;
use GestionSigcerBundle\Entity\DetalleSolicitud;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


/**
 * @Route("/sigcer/solic")
 */

class GestionSolicitudesController extends Controller
{

	////////////////Pais Origen//////////////////////////////////
    /**
     * @Route("/addgs", name="sigcer_add_grupos_solicitudes")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addGrupoSolicitudes()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(GrupoSolicitud::class);
        $grupos = $repository->findAllGrupos();

        $grupo = new GrupoSolicitud();
        $formInf = $this->createForm(\GestionSigcerBundle\Form\GrupoSolicitudType::class, 
                                      $grupo, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_grupos_solicitudes_procesar')]);
        return $this->render('@GestionSigcer/altaGrupoSolicitud.html.twig', 
                            ['form' => $formInf->createView(), 'grupos' => $grupos]);
    }

    /**
     * @Route("/addgspr", name="sigcer_add_grupos_solicitudes_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function grupoSolicitudesProcesar(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(GrupoSolicitud::class);
        $grupos = $repository->findAllGrupos();

        $grupo = new GrupoSolicitud();
        $form = $this->createForm(\GestionSigcerBundle\Form\GrupoSolicitudType::class, 
                                      $grupo, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_grupos_solicitudes_procesar')]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grupo);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Grupo de solicitudes generado exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_grupos_solicitudes');
        }
        return $this->render('@GestionSigcer/altaGrupoSolicitud.html.twig', 
                            ['form' => $form->createView(), 'grupos' => $grupos]);
    }

  ////////////////Agregar Tropas a Grupo de Solicitud//////////////////////////////////
    /**
     * @Route("/addtrgs/{gpo}", name="sigcer_add_tropa_grupo_solicitud")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function adTropaGrupoSolicitud($gpo)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->find(GrupoSolicitud::class, $gpo);

        $tropa = new TropaSolicitud();
        $tropa->setGrupoSolicitud($grupo);
        $formInf = $this->createForm(\GestionSigcerBundle\Form\TropaSolicitudType::class, 
                                      $tropa, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_tropa_grupo_solicitud_procesar', array('gpo' => $gpo))]);
        return $this->render('@GestionSigcer/addTropaAGrupo.html.twig', 
                            ['form' => $formInf->createView(), 'grupo' => $grupo]);
    }

    /**
     * @Route("/addtrgspr/{gpo}", name="sigcer_add_tropa_grupo_solicitud_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function adTropaGrupoSolicitudProcesar($gpo, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->find(GrupoSolicitud::class, $gpo);

        $tropa = new TropaSolicitud();
        $tropa->setGrupoSolicitud($grupo);
        $form = $this->createForm(\GestionSigcerBundle\Form\TropaSolicitudType::class, 
                                      $tropa, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_tropa_grupo_solicitud_procesar', array('gpo' => $gpo))]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $grupo->addTropa($tropa);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tropa);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Tropa agregada exitosamente al grupo de solicitudes!'
                            );
            return $this->redirectToRoute('sigcer_add_tropa_grupo_solicitud', ['gpo' => $gpo]);
        }
        return $this->render('@GestionSigcer/addTropaAGrupo.html.twig', 
                            ['form' => $form->createView()]);
    }

  ////////////////Agregar Solicitud a Grupo de Solicitudes//////////////////////////////////
    /**
     * @Route("/addsol/{gpo}", name="sigcer_add_solictud_a_grupo")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addSolicitud($gpo)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->find(GrupoSolicitud::class, $gpo);

        $solicitud = new Solicitud();
        $solicitud->setGrupo($grupo);
        $formInf = $this->createForm(\GestionSigcerBundle\Form\SolicitudType::class, 
                                      $solicitud, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_solictud_a_grupo_procesar', array('gpo' => $gpo))]);
        return $this->render('@GestionSigcer/addSolicitudAGrupo.html.twig', 
                            ['form' => $formInf->createView(), 'grupo' => $grupo]);
    }

    /**
     * @Route("/addsolproc/{gpo}", name="sigcer_add_solictud_a_grupo_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addSolicitudProcesar($gpo, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->find(GrupoSolicitud::class, $gpo);

        $solicitud = new Solicitud();
        $solicitud->setGrupo($grupo);
        $form = $this->createForm(\GestionSigcerBundle\Form\SolicitudType::class, 
                                      $solicitud, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_solictud_a_grupo_procesar', array('gpo' => $gpo))]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $grupo->addSolicitude($solicitud);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($solicitud);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Solicitud agregada exitosamente al grupo de solicitudes!'
                            );
            return $this->redirectToRoute('sigcer_add_solictud_a_grupo', ['gpo' => $gpo]);
        }
        return $this->render('@GestionSigcer/addSolicitudAGrupo.html.twig', 
                            ['form' => $form->createView(), 'grupo' => $grupo]);
    }

    /**
     * @Route("/editsol/{sol}", name="sigcer_editar_solicitud")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editSolicitud($sol)
    {
        $em = $this->getDoctrine()->getManager();
        $solicitud = $em->find(Solicitud::class, $sol);

        $formInf = $this->createForm(\GestionSigcerBundle\Form\SolicitudType::class, 
                                      $solicitud, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_editar_solicitud_procesar', array('sol' => $sol))]);
        return $this->render('@GestionSigcer/editSolicitud.html.twig', 
                            ['form' => $formInf->createView()]);
    }

    /**
     * @Route("/editsolproc/{sol}", name="sigcer_editar_solicitud_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editSolicitudProcesar($sol, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $solicitud = $em->find(Solicitud::class, $sol);

        $form = $this->createForm(\GestionSigcerBundle\Form\SolicitudType::class, 
                                      $solicitud, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_editar_solicitud_procesar', array('sol' => $sol))]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em->flush();
            $this->addFlash(
                                'sussecc',
                                'Solicitud actualizada exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_solictud_a_grupo', ['gpo' => $solicitud->getGrupo()->getId()]);
        }
        return $this->render('@GestionSigcer/editSolicitud.html.twig', 
                            ['form' => $form->createView()]);
    }


  ////////////////Agregar Articulos a Solicitudes//////////////////////////////////
    /**
     * @Route("/delart/{art}", name="sigcer_delete_detalle")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteDetalleArticulo($art)
    {
        $em = $this->getDoctrine()->getManager();
        $detalle = $em->find(DetalleSolicitud::class, $art);
        $sol = $detalle->getSolicitud()->getId();
        $em->remove($detalle);
        $em->flush();
            $this->addFlash(
                                'sussecc',
                                'Articulo eliminado exitosamente del detalle!'
                            );
        return $this->redirectToRoute('sigcer_add_articulos_a_solicitud', ['sol' => $sol]);
    }

    /**
     * @Route("/editarsol/{art}", name="sigcer_add_articulos_editar")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarArticuloDetalle($art)
    {
        $em = $this->getDoctrine()->getManager();
        $detalle = $em->find(DetalleSolicitud::class, $art);

        $formInf = $this->createForm(\GestionSigcerBundle\Form\DetalleSolicitudType::class, 
                                      $detalle, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_articulos_editar_procesar', array('art' => $art))]);
        return $this->render('@GestionSigcer/editarArticulo.html.twig', 
                            ['form' => $formInf->createView(), 'sol' => $detalle->getSolicitud()]);
    }

    /**
     * @Route("/editarsolproc/{art}", name="sigcer_add_articulos_editar_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarArticuloDetalleProcesar($art, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $detalle = $em->find(DetalleSolicitud::class, $art);

        $form = $this->createForm(\GestionSigcerBundle\Form\DetalleSolicitudType::class, 
                                      $detalle, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_articulos_editar_procesar', array('art' => $art))]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em->flush();
            $this->addFlash(
                                'sussecc',
                                'Producto modificado exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_articulos_a_solicitud', ['sol' => $detalle->getSolicitud()->getId()]);
        }
        return $this->render('@GestionSigcer/editarArticulo.html.twig', 
                            ['form' => $form->createView(), 'sol' => $detalle->getSolicitud()]);
    }



    /**
     * @Route("/addart/{sol}", name="sigcer_add_articulos_a_solicitud")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addArticuloSolicitud($sol)
    {
        $em = $this->getDoctrine()->getManager();
        $solicitud = $em->find(Solicitud::class, $sol);

        $detalle = new DetalleSolicitud();
        $detalle->setSolicitud($solicitud);
        $formInf = $this->createForm(\GestionSigcerBundle\Form\DetalleSolicitudType::class, 
                                      $detalle, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_articulos_a_solicitud_procesar', array('sol' => $sol))]);
        return $this->render('@GestionSigcer/addArticulo.html.twig', 
                            ['form' => $formInf->createView(), 'sol' => $solicitud]);
    }

    /**
     * @Route("/addartproc/{sol}", name="sigcer_add_articulos_a_solicitud_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function articuloSolicitudProcesar($sol, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $solicitud = $em->find(Solicitud::class, $sol);

        $detalle = new DetalleSolicitud();
        $detalle->setSolicitud($solicitud);
        $form = $this->createForm(\GestionSigcerBundle\Form\DetalleSolicitudType::class, 
                                      $detalle, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_articulos_a_solicitud_procesar', array('sol' => $sol))]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $solicitud->addDetalle($detalle);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($detalle);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Articulo agregado exitosamente a la solicitud!'
                            );
            return $this->redirectToRoute('sigcer_add_articulos_a_solicitud', ['sol' => $sol]);
        }
        return $this->render('@GestionSigcer/addArticulo.html.twig', 
                            ['form' => $form->createView(), 'sol' => $solicitud]);
    }

    /**
     * @Route("/delsol/{sol}", name="sigcer_delete_solicitud")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteSolicitud($sol)
    {
        $em = $this->getDoctrine()->getManager();
        $solicitud = $em->find(Solicitud::class, $sol);

        $grupo = $solicitud->getGrupo()->getId();
        try{
            $em->remove($solicitud);
            $em->flush();
            $this->addFlash(
                                    'sussecc',
                                    'Solicitud eliminada exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_solictud_a_grupo', ['gpo' => $grupo]);        
          }
          catch (\Exception $e){
                                $this->addFlash(
                                                        'error',
                                                        'Ha ocurrido un error al intentar eliminar la solicitud!'
                                                );
                                return $this->redirectToRoute('sigcer_add_solictud_a_grupo', ['gpo' => $grupo]);        
                              
          }
      }

  ////////////////Modificaciones multiples//////////////////////////////////
    /**
     * @Route("/mdfml/{gpo}", name="sigcer_modificacion_multiple")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function modMultSol($gpo, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->find(GrupoSolicitud::class, $gpo);
        $form =    $this->createFormBuilder()
                        ->add('zona', 
                              EntityType::class, 
                              [
                              'class' => Region::class
                                ])
                        ->add('grupo', 
                              EntityType::class, 
                              [
                              'class' => GrupoSolicitud::class,
                               'choices' => [$grupo]
                                ])
                        ->add('load', SubmitType::class, ['label' => 'Cargar solicitudes'])      
                        ->getForm();
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            $data = $form->getData();
            $repository = $em->getRepository(Solicitud::class);
            $solicitudes = $repository->findSolicitudes($data['grupo'], $data['zona']);
            $forms = array();
            foreach ($solicitudes as $s) {
              foreach ($s->getDetalles() as $d) {
                $forms[$d->getId()] = $this->getFormUpdateDetalle($d)->createView();
              }
            }

            return $this->render('@GestionSigcer/modificacionMultiple.html.twig', ['region' => $data['zona'], 'form' => $form->createView(), 'solicitudes' => $solicitudes, 'formUpd' => $forms]);
        }

        return $this->render('@GestionSigcer/modificacionMultiple.html.twig', ['region' => '', 'form' => $form->createView()]);
    }

    private function getFormUpdateDetalle($detalle)
    {
        $form =    $this->createFormBuilder()
                        ->add('cant',TextType::class, [
                                                        'data' => $detalle->getCantidad(),
                                                        'attr' => ['data-fpb' => strval($detalle->getArticulo()->getAjusteBruto()),
                                                                   'data-fpn' => strval($detalle->getArticulo()->getAjusteNeto())],
                                                      ])
                        ->add('pbruto',TextType::class, ['data' => $detalle->getPesoBruto()])
                        ->add('pneto',TextType::class, ['data' => $detalle->getPesoNeto()])
                        ->add('load', SubmitType::class, ['label' => 'Modificar'])   
                        ->setAction($this->generateUrl('sigcer_update_detalle', array('deta' => $detalle->getId())))
                        ->setMethod('POST')   
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/updeta/{deta}", name="sigcer_update_detalle")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function updateDetalle($deta, Request $request)
    {
      try
      {
          $em = $this->getDoctrine()->getManager();
          $detalle = $em->find(DetalleSolicitud::class, $deta);
          $form = $this->getFormUpdateDetalle($detalle);
          $form->handleRequest($request);
          $data = $form->getData();
          $detalle->setCantidad($data['cant']);
          $detalle->setPesoBruto($data['pbruto']);
          $detalle->setPesoNeto($data['pneto']);
          $em->flush();
          return new JsonResponse(array('status' => true));
      }
      catch (\Exception $e){
                            return new JsonResponse(array('status' => false, 'message' => 'No se ha podido realizar la actualizacion'));
      }
    }

    /**
     * @Route("/grtmlt/{gpo}", name="sigcer_generate_multiples", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function generateMultiplesSolicitudes($gpo, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->find(GrupoSolicitud::class, $gpo);
        $form =    $this->createFormBuilder()
                        ->add('tropa', 
                               EntityType::class, 
                               [
                                  'class' => TropaSolicitud::class,
                                  'choices' => $grupo->getTropas()
                                ])
                        ->add('region', 
                               EntityType::class, 
                               [
                                  'class' => Region::class
                                ])
                        ->add('precinto',TextType::class, ['data' => $grupo->getInicioPrecinto()])
                        ->add('generar', SubmitType::class, ['label' => 'Generar Solicitudes'])   
                        ->setMethod('POST')   
                        ->getForm();

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $data = $form->getData();
            $allZonas = $em->getRepository(Zona::class)->findAll();
            $allProductos = $em->getRepository(Producto::class)->findAll();
            $lastZona = null;
            $inicioPrecinto = $data['precinto'];
            $tropa = $data['tropa'];
            $lastPrecinto = 0;
            foreach ($allZonas as $zona) 
            {
              if ($zona->getRegion() == $data['region'])
              {
                $solicitud = new Solicitud();

                if (($lastZona) && ($lastZona->getCamion() == $zona->getCamion())) //la ultima zona procesada tiene el mismo camion que la actual, se debe copiar el ultimo numero de precinto
                {
                  $precinto = $lastPrecinto;              
                }
                else //o es la primer solicitud o el ultimo camion es ditinto al actual, se debe generar la numeracion
                {
                    $precinto = "";
                    for ($i = 0; $i < $zona->getCamion()->getCantPrecintos(); $i++)
                    {
                      if (!$precinto)
                      {
                        $precinto = strval($inicioPrecinto);
                      }
                      else
                      {
                          $aux = strval($inicioPrecinto);
                          $precinto.="/".substr($aux, -4);
                      }
                      $inicioPrecinto++;
                    }
                }
                $solicitud->setPrecintoSenasa($precinto);
                $solicitud->setZona($zona);
                $solicitud->setLugarDestino($zona->getZona());
                $solicitud->setPrecintos($zona->getCamion()->getCantPrecintos());
                $solicitud->setCamion($zona->getCamion());

                foreach ($allProductos as $prod) 
                {
                    $detalle = new DetalleSolicitud();
                    $detalle->setArticulo($prod);
                    $detalle->setTropa($tropa);
                    $detalle->setEnvasePrimario($prod->getEnvasePrimario());
                    $detalle->setEnvaseSecundario($prod->getEnvaseSecundario());
                    $detalle->setSolicitud($solicitud);
                    $solicitud->addDetalle($detalle);
                }
                $solicitud->setGrupo($grupo);
                $grupo->addSolicitude($solicitud);
                $em->persist($solicitud);
                $lastPrecinto = $precinto;
                $lastZona = $zona;
              }
            }
            $em->flush();
        }
        return $this->render('@GestionSigcer/generarMultiples.html.twig', ['form' => $form->createView(), 'grupo' => $grupo]);
    }

    /**
     * @Route("/genxml/{gpo}", name="sigcer_generar_archivos")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function generateXML($gpo)
    {
        $xsd = $this->get('kernel')->getRootDir() . '/../web/resources/multiplesSolicitudesCarnicos.xsd';

        $zip = $this->get('kernel')->getRootDir() . '/../web/solxml/';


        $em = $this->getDoctrine()->getManager();
        $grupo = $em->find(GrupoSolicitud::class, $gpo);

        $tr = $grupo->getTropa();
        $files = array();
        $generateZip = false;
        foreach ($grupo->getSolicitudes() as $sol) 
        {
            $dom = new \DOMDocument('1.0', 'utf-8');
            $dom->xmlStandalone = true;
            $solicitud = $dom->createElement('se:solicitud');
            $solicitud->setAttribute( "xmlns:se", "http://www.senasa.gov.ar/solicitud" );
            $solicitud->setAttribute( "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance" );
            $solicitud->setAttribute( "xsi:schemaLocation", "http://www.senasa.gov.ar/solicitud solicitudCertCarnicos.xsd");
            $version = $dom->createElement( "se:version", $grupo->getVersion());
            $solicitud->appendChild($version);
            $tpp = $dom->createElement( "se:tropasPorProducto", 'true');
            $solicitud->appendChild($tpp);
            $paisDestino = $dom->createElement( "se:paisDestino", $grupo->getPaisDestino()->getCodigo());
            $solicitud->appendChild($paisDestino);
            $establecimiento = $dom->createElement( "se:establecimiento", $grupo->getCodigoEstablecimiento());
            $solicitud->appendChild($establecimiento);
            $lugarDestino = $dom->createElement( "se:lugarDestino", $sol->getLugarDestino());
            $solicitud->appendChild($lugarDestino);
            
            $detalles = $dom->createElement('se:detalles');
            $ok = false;
            foreach ($sol->getDetalles() as $deta)
            {
                if ($deta->getCantidad())
                {
                    $detalle = $dom->createElement('se:detalle');
                        $producto = $dom->createElement('se:producto');
                          $codigo = $dom->createElement('se:codigoProducto', $deta->getArticulo()->getCodigoCapa());
                        $producto->appendChild($codigo);
                    $detalle->appendChild($producto);
                    $congelado = $tr->getFechaCongelado()->format('Y-m-d');
                    $tropa = $dom->createElement('se:tropa');
                    $tropa->setAttribute( "fechaDeCongelado", "" .$congelado );
                    $tropa->setAttribute( "fechaDeElaboracion", "".$tr->getFechaElaboracion()->format('Y-m-d'));
                    $tropa->setAttribute( "fechaDeFaena", "".$tr->getFechaFaena()->format('Y-m-d') );
                    $tropa->setAttribute( "fechaDeVencimiento", ''.$tr->getFechaVto()->format('Y-m-d'));
                    $tropa->setAttribute( "numeroTropa", ''.$tr->getNumeroTropa() );
                    $tropa->setAttribute( "lote", ''.$tr->getLote());
                    $detalle->appendChild($tropa);
                    $detalle->appendChild($dom->createElement('se:pesoNeto', $deta->getPesoNeto()));
                    $detalle->appendChild($dom->createElement('se:pesoBruto', $deta->getPesoBruto()));
                    $detalle->appendChild($dom->createElement('se:cantidad', $deta->getCantidad()));
                    $detalle->appendChild($dom->createElement('se:envasePrimario', $deta->getEnvasePrimario()->getNombre()));
                    $detalle->appendChild($dom->createElement('se:envaseSecundario', $deta->getEnvaseSecundario()->getNombre()));
                    $detalle->appendChild($dom->createElement('se:codEnvasePrimarioSENASA', $deta->getEnvasePrimario()->getCodigo()));
                    $detalle->appendChild($dom->createElement('se:codEnvaseSecundarioSENASA', $deta->getEnvaseSecundario()->getCodigo()));
                    $detalle->appendChild($dom->createElement('se:marca', $deta->getArticulo()->getMarca()));
                    $detalles->appendChild($detalle);
                    $ok = true;
                }
            }
            if ($ok){
                $generateZip = true;
                $solicitud->appendChild($detalles);
                $solicitud->appendChild($dom->createElement('se:precintoSENASA', $sol->getPrecintoSenasa()));
                $camion = $dom->createElement('se:camion');
                  $camion->appendChild($dom->createElement('se:tipoDeTransporte', $sol->getCamion()->getTipo()));
                  $camion->appendChild($dom->createElement('se:patenteCamion', $sol->getCamion()->getPatente()));
                  $camion->appendChild($dom->createElement('se:habilitacionTransporte', $sol->getCamion()->getSenasa()));
                $solicitud->appendChild($camion);
                $solicitud->appendChild($dom->createElement('se:temperatura', $sol->getTemperatura()));
                $solicitud->appendChild($dom->createElement('se:territorio', 'AR-1'));
                $solicitud->appendChild($dom->createElement('se:rolDelEstablecimiento', $grupo->getRoleEstablecimiento()));
                $dom->appendChild($solicitud);
              //  $dom->schemaValidate($xsd);

                $name = $zip."sol_".$sol->getId().".xml";
                $dom->save($name);
                $files["sol_".$sol->getId().".xml"] = $name;
            }
        }
        if ($generateZip)
        {
            $archivo = new \ZipArchive();
            $zipName = 'solicitudesFecha'.$grupo->getFecha()->format('dmY').'.zip';
            $archivo->open($zipName,  \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            foreach ($files as $k => $v) {
                $archivo->addFile($v,  $k); 
            }
           
           // 
           /* $response = new Response();
            $response->setContent(readfile($zipName));
            $response->headers->set('Content-Type', 'application/zip');
            $response->header('Content-disposition: attachment; filename='.$grupo->getFecha()->format('d_m_Y').'.zip');
            $response->header('Content-Length: ' . filesize($zipName));
            $response->readfile($zipName);
            return $response;*/

       
        $response = new Response(file_get_contents($archivo->filename));
        
        $archivo->close();

        $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'solicitudes_fecha_'.$grupo->getFecha()->format('d_m_Y') . '.zip');
        $response->headers->set('Content-Disposition', $d);
       
        
       
        return $response;


        }

    }
    private function getDOM()
    {

    }

}
