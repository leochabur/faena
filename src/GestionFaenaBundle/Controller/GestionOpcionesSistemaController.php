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

class GestionOpcionesSistemaController extends Controller
{

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
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
