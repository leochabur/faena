<?php

namespace GestionSigcerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use GestionSigcerBundle\Entity\GrupoSolicitud;
use GestionSigcerBundle\Entity\TropaSolicitud;
use GestionSigcerBundle\Entity\opciones\Envase;
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
            $form = $this->createForm(\GestionSigcerBundle\Form\GrupoSolicitudType::class, 
                                       new GrupoSolicitud(), 
                                          ['method' => 'POST',
                                           'action' => $this->generateUrl('sigcer_add_grupos_solicitudes_procesar')]);
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
                            ['form' => $formInf->createView()]);
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
            return $this->redirectToRoute('sigcer_add_grupos_solicitudes');
        }
        return $this->render('@GestionSigcer/addTropaAGrupo.html.twig', 
                            ['form' => $form->createView()]);
    }

}
