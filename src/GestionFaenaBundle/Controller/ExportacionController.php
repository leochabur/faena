<?php

namespace GestionFaenaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use GestionFaenaBundle\Entity\exportacion\TipoPallet;
use GestionFaenaBundle\Form\exportacion\TipoPalletType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ExportacionController extends Controller
{

      /**
     * @Route("/export/palletalta", name="exportaciones_alta_tipo_pallet", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function altaTipoPallet(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        

        $tipoPallet = new TipoPallet();
        $route = $this->generateUrl('exportaciones_alta_tipo_pallet');
        $form = $this->getFormAltaTipoPallet($tipoPallet, $route);
        if ($request->isMethod('POST'))
        {
          $form->handleRequest($request);
          if ($form->isValid())
          {
              $em->persist($tipoPallet);
              $em->flush();
              $this->addFlash('success', 'Tipo Pallet generado exitosamente');
              return $this->redirectToRoute('exportaciones_alta_tipo_pallet');
          }
        }
        $tiposPallet = $em->getRepository(TipoPallet::class)->findAllTipoPallets();
        return $this->render('@GestionFaena/exportacion/abmTipoPallet.html.twig', ['label' => 'Nuevo', 'form' => $form->createView(), 'tipos' => $tiposPallet]);
    }

    private function getFormAltaTipoPallet($tipoPallet, $route)
    {
        return $this->createForm(TipoPalletType::class, 
                                 $tipoPallet, 
                                 [
                                  'method' => 'POST',
                                  'action' => $route
                                  ]);
    }

      /**
     * @Route("/export/edittp/{id}", name="exportaciones_editar_tipo_pallet", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarTipoPallet($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $tipoPallet = $em->find(TipoPallet::class, $id);
        $route = $this->generateUrl('exportaciones_editar_tipo_pallet_procesar', ['id' => $id]);
        $form = $this->getFormAltaTipoPallet($tipoPallet, $route);

        return $this->render('@GestionFaena/exportacion/abmTipoPallet.html.twig', ['form' => $form->createView(), 'label' => 'Modificar']);
    }

      /**
     * @Route("/export/edittproc/{id}", name="exportaciones_editar_tipo_pallet_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarTipoPalletProcesar($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $tipoPallet = $em->find(TipoPallet::class, $id);
        $route = $this->generateUrl('exportaciones_editar_tipo_pallet_procesar', ['id' => $id]);
        $form = $this->getFormAltaTipoPallet($tipoPallet, $route);

        $form->handleRequest($request);
        if ($form->isValid())
        {
          $em->flush();
          $this->addFlash('success', 'Tipo Pallet modificado exitosamente');
          return $this->redirectToRoute('exportaciones_alta_tipo_pallet');
        }

        return $this->render('@GestionFaena/exportacion/abmTipoPallet.html.twig', ['form' => $form->createView(), 'label' => 'Modificar']);
    }

}
