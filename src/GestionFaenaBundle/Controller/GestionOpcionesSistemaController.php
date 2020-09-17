<?php

namespace GestionFaenaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GestionFaenaBundle\Entity\gestionBD\Granja;
use GestionFaenaBundle\Entity\gestionBD\Transportista;
use GestionFaenaBundle\Entity\gestionBD\Ciudad;
use GestionFaenaBundle\Entity\gestionBD\Cargador;
use GestionFaenaBundle\Entity\gestionBD\Sucursal;
use GestionFaenaBundle\Entity\gestionBD\Consignatario;
use GestionFaenaBundle\Entity\gestionBD\Remito;
use GestionFaenaBundle\Entity\gestionBD\Anexo;
use GestionFaenaBundle\Entity\opciones\InformeProceso;

class GestionOpcionesSistemaController extends Controller
{

    /**
     * @Route("/addinfproc", name="bd_add_informe_proceso")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addInformeProceso()
    {
       // $em = $this->getDoctrine()->getManager();
       // $granjas = $em->getRepository(Granja::class)->findAll();

        $informe = new InformeProceso();
        $formInf = $this->createForm(\GestionFaenaBundle\Form\opciones\InformeProcesoType::class, 
                                      $informe, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('bd_add_informe_proceso_procesar')]);
        return $this->render('@GestionFaena/options/abmInforme.html.twig', 
                            ['form' => $formInf->createView()]);
    }

    /**
     * @Route("/iprproc", name="bd_add_informe_proceso_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormArtAtrConcepto(Request $request)
    {
        $informe = new InformeProceso();
        $form = $this->createForm(\GestionFaenaBundle\Form\opciones\InformeProcesoType::class, 
                                      $informe, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('bd_add_informe_proceso_procesar')]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($informe);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Configuracion almacenada exitosamente!'
                            );
            return $this->redirectToRoute('bd_add_informe_proceso');
        }
        return $this->render('@GestionFaena/options/abmInforme.html.twig', 
                            ['form' => $form->createView()]);
    }

///////////////////////MANEJO ENTIDADES EXTERNAS /////////////////////////////////////////////////////
    /**
     * @Route("/addgja", name="bd_add_entity_granja")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntityGranjaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $granjas = $em->getRepository(Granja::class)->findAll();

        $granja = new Granja();
        $fGranja = $this->createForm(\GestionFaenaBundle\Form\gestionBD\GranjaType::class, 
                                  $granja, 
                                  ['method' => 'POST',
                                   'action' => $this->generateUrl('save_entidad_externa', array('ee' => 1))]);
        return $this->render('@GestionFaena/gestionBD/abmGranja.html.twig', 
                            ['form' => $fGranja->createView(), 'granjas' => $granjas]);
    }

    /**
     * @Route("/addtrs", name="bd_add_entity_transportista")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntityTransportistaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $transportistas = $em->getRepository(Transportista::class)->findAll();
        $trans = new Transportista();
        $fTrans = $this->createForm(\GestionFaenaBundle\Form\gestionBD\TransportistaType::class, 
                                  $trans, 
                                  ['method' => 'POST',
                                   'action' => $this->generateUrl('save_entidad_externa', array('ee' => 2))]);
        return $this->render('@GestionFaena/gestionBD/abmTransportista.html.twig', 
                            ['trans' => $fTrans->createView(), 'transportistas' => $transportistas]);
    }

    /**
     * @Route("/addcty", name="bd_add_entity_ciudad")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntityCiudadAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ciudades = $em->getRepository(Ciudad::class)->findAll();

        $ciudad = new Ciudad();
        $form = $this->createForm(\GestionFaenaBundle\Form\gestionBD\CiudadType::class, 
                                  $ciudad, 
                                  ['method' => 'POST',
                                   'action' => $this->generateUrl('save_entidad_externa', array('ee' => 3))]);
        return $this->render('@GestionFaena/gestionBD/abmCiudad.html.twig', 
                            ['form' => $form->createView(), 'ciudades' => $ciudades]);
    }

    /**
     * @Route("/addcrg", name="bd_add_entity_cargador")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntityCargadorAction()
    {
        $em = $this->getDoctrine()->getManager();
        $cargadores = $em->getRepository(Cargador::class)->findAll();

        $cargador = new Cargador();
        $form = $this->createForm(\GestionFaenaBundle\Form\gestionBD\CargadorType::class, 
                                  $cargador, 
                                  ['method' => 'POST',
                                   'action' => $this->generateUrl('save_entidad_externa', array('ee' => 4))]);
        return $this->render('@GestionFaena/gestionBD/abmCargador.html.twig', 
                            ['form' => $form->createView(), 'cargadores' => $cargadores]);
    }

    /**
     * @Route("/addsuc", name="bd_add_entity_sucursal")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntitySucursalAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sucursales = $em->getRepository(Sucursal::class)->findAll();

        $sucursal = new Sucursal();
        $form = $this->createForm(\GestionFaenaBundle\Form\gestionBD\SucursalType::class, 
                                  $sucursal, 
                                  ['method' => 'POST',
                                   'action' => $this->generateUrl('save_entidad_externa', array('ee' => 5))]);
        return $this->render('@GestionFaena/gestionBD/abmSucursal.html.twig', 
                            ['form' => $form->createView(), 'sucursales' => $sucursales]);
    }

    /**
     * @Route("/addcons", name="bd_add_entity_consignatario")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntityConsignatarioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $consignatarios = $em->getRepository(Consignatario::class)->findAll();

        $consignatario = new Consignatario();
        $form = $this->createForm(\GestionFaenaBundle\Form\gestionBD\ConsignatarioType::class, 
                                  $consignatario, 
                                  ['method' => 'POST',
                                   'action' => $this->generateUrl('save_entidad_externa', array('ee' => 6))]);
        return $this->render('@GestionFaena/gestionBD/abmConsignatario.html.twig', 
                            ['form' => $form->createView(), 'consignatarios' => $consignatarios]);
    }

    /**
     * @Route("/addrto", name="bd_add_entity_remito")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntityRemitoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $remitos = $em->getRepository(Remito::class)->findAll();

        $remito = new Remito();
        $form = $this->createForm(\GestionFaenaBundle\Form\gestionBD\RemitoType::class, 
                                  $remito, 
                                  ['method' => 'POST',
                                   'action' => $this->generateUrl('save_entidad_externa', array('ee' => 7))]);
        return $this->render('@GestionFaena/gestionBD/abmRemito.html.twig', 
                            ['form' => $form->createView(), 'remitos' => $remitos]);
    }

    /**
     * @Route("/addanxo", name="bd_add_entity_anexo")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntityAnexoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $anexos = $em->getRepository(Anexo::class)->findAll();

        $anexo = new Anexo();
        $form = $this->createForm(\GestionFaenaBundle\Form\gestionBD\AnexoType::class, 
                                  $anexo, 
                                  ['method' => 'POST',
                                   'action' => $this->generateUrl('save_entidad_externa', array('ee' => 8))]);
        return $this->render('@GestionFaena/gestionBD/abmAnexo.html.twig', 
                            ['form' => $form->createView(), 'anexos' => $anexos]);
    }

    /**
     * @Route("/saveEntExt/{ee}", name="save_entidad_externa", methods={"POST"})
     */
    public function saveEntidadExterna($ee, Request $request)
    {   
        $entityManager = $this->getDoctrine()->getManager();
        if ($ee == 1){
            $granjas = $entityManager->getRepository(Granja::class)->findAll();
            $granja = new \GestionFaenaBundle\Entity\gestionBD\Granja();
            $fGranja = $this->createForm(\GestionFaenaBundle\Form\gestionBD\GranjaType::class, 
                                      $granja, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('save_entidad_externa', array('ee' => 1))]);
            $fGranja->handleRequest($request);
            if ($fGranja->isValid()){                
                $entityManager->persist($granja);
                $entityManager->flush();
                $this->addFlash(
                        'sussecc',
                        'Granja almacenada exitosamente!'
                    );
                return $this->redirectToRoute('bd_add_entity_granja');
            }
            return $this->render('@GestionFaena/gestionBD/abmGranja.html.twig', 
                            ['granjas' => $granjas,'form' => $fGranja->createView()]);
        }
        elseif ($ee == 2)
        {
            $trans = new \GestionFaenaBundle\Entity\gestionBD\Transportista();
            $transportistas = $entityManager->getRepository(Transportista::class)->findAll();
            $fTrans = $this->createForm(\GestionFaenaBundle\Form\gestionBD\TransportistaType::class, 
                                      $trans, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('save_entidad_externa', array('ee' => 2))]);
            $fTrans->handleRequest($request);
            if ($fTrans->isValid())
            {
                $entityManager->persist($trans);
                $entityManager->flush();
                $this->addFlash(
                        'sussecc',
                        'Transportista almacenado exitosamente!'
                    );
                return $this->redirectToRoute('bd_add_entity_transportista');
            }  
            return $this->render('@GestionFaena/gestionBD/abmTransportista.html.twig', 
                                ['transportistas' => $transportistas, 'trans' => $fTrans->createView()]);
        }
        elseif ($ee == 3)
        {
            $ciudad = new Ciudad();
            $ciudades = $entityManager->getRepository(Ciudad::class)->findAll();
            $form = $this->createForm(\GestionFaenaBundle\Form\gestionBD\CiudadType::class, 
                                      $ciudad, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('save_entidad_externa', array('ee' => 3))]);
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $entityManager->persist($ciudad);
                $entityManager->flush();
                $this->addFlash(
                        'sussecc',
                        'Ciudad almacenada exitosamente!'
                    );
                return $this->redirectToRoute('bd_add_entity_ciudad');
            }  
            return $this->render('@GestionFaena/gestionBD/abmCiudad.html.twig', 
                                ['ciudades' => $ciudades, 'form' => $form->createView()]);
        }
        elseif ($ee == 4)
        {
            $cargador = new \GestionFaenaBundle\Entity\gestionBD\Cargador();
            $cargadores = $entityManager->getRepository(Cargador::class)->findAll();
            $fCarg = $this->createForm(\GestionFaenaBundle\Form\gestionBD\CargadorType::class, 
                                      $cargador, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('save_entidad_externa', array('ee' => 4))]);
            $fCarg->handleRequest($request);
            if ($fCarg->isValid())
            {
                $entityManager->persist($cargador);
                $entityManager->flush();
                $this->addFlash(
                        'sussecc',
                        'Cargador almacenado exitosamente!'
                    );
                return $this->redirectToRoute('bd_add_entity_cargador');
            }  
            return $this->render('@GestionFaena/gestionBD/abmCargador.html.twig', 
                                ['cargadores' => $cargadores, 'form' => $fCarg->createView()]);
        } 
        elseif ($ee == 5)
        {
            $sucursal = new Sucursal();
            $sucursales = $entityManager->getRepository(Sucursal::class)->findAll();
            $fSuc = $this->createForm(\GestionFaenaBundle\Form\gestionBD\SucursalType::class, 
                                      $sucursal, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('save_entidad_externa', array('ee' => 5))]);
            $fSuc->handleRequest($request);
            if ($fSuc->isValid())
            {
                $entityManager->persist($sucursal);
                $entityManager->flush();
                $this->addFlash(
                        'sussecc',
                        'Sucursal almacenada exitosamente!'
                    );
                return $this->redirectToRoute('bd_add_entity_sucursal');
            }  
            return $this->render('@GestionFaena/gestionBD/abmSucursal.html.twig', 
                                ['sucursales' => $sucursales, 'form' => $fSuc->createView()]);
        } 
        elseif ($ee == 6)
        {

            $consignatarios = $entityManager->getRepository(Consignatario::class)->findAll();

            $consignatario = new Consignatario();
            $fCons = $this->createForm(\GestionFaenaBundle\Form\gestionBD\ConsignatarioType::class, 
                                      $consignatario, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('save_entidad_externa', array('ee' => 6))]);
            $fCons->handleRequest($request);
            if ($fCons->isValid())
            {
                $entityManager->persist($consignatario);
                $entityManager->flush();
                $this->addFlash(
                        'sussecc',
                        'Consignatario almacenado exitosamente!'
                    );
                return $this->redirectToRoute('bd_add_entity_consignatario');
            }  
            return $this->render('@GestionFaena/gestionBD/abmConsignatario.html.twig', 
                                ['consignatarios' => $consignatarios, 'form' => $fCons->createView()]);
        }
        elseif ($ee == 7)
        {

            $remitos = $entityManager->getRepository(Remito::class)->findAll();

            $remito = new Remito();
            $fRem = $this->createForm(\GestionFaenaBundle\Form\gestionBD\RemitoType::class, 
                                      $remito, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('save_entidad_externa', array('ee' => 7))]);
            $fRem->handleRequest($request);
            if ($fRem->isValid())
            {
                $entityManager->persist($remito);
                $entityManager->flush();
                $this->addFlash(
                        'sussecc',
                        'Remito almacenado exitosamente!'
                    );
                return $this->redirectToRoute('bd_add_entity_remito');
            }  
            return $this->render('@GestionFaena/gestionBD/abmRemito.html.twig', 
                                ['form' => $fRem->createView(), 'remitos' => $remitos]);
        }
        elseif ($ee == 8)
        {
            $anexos = $entityManager->getRepository(Anexo::class)->findAll();

            $anexo = new Anexo();
            $fRem = $this->createForm(\GestionFaenaBundle\Form\gestionBD\AnexoType::class, 
                                      $anexo, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('save_entidad_externa', array('ee' => 7))]);
            $fRem->handleRequest($request);
            if ($fRem->isValid())
            {
                $entityManager->persist($anexo);
                $entityManager->flush();
                $this->addFlash(
                        'sussecc',
                        'Anexo almacenado exitosamente!'
                    );
                return $this->redirectToRoute('bd_add_entity_anexo');
            }  
            return $this->render('@GestionFaena/gestionBD/abmAnexo.html.twig', 
                                ['form' => $fRem->createView(), 'anexos' => $anexos]);
        }               
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
