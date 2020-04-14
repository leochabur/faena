<?php

namespace GestionSigcerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use GestionSigcerBundle\Entity\opciones\PaisDestino;
use GestionSigcerBundle\Entity\opciones\Envase;
use GestionSigcerBundle\Entity\opciones\Cliente;
use GestionSigcerBundle\Entity\opciones\Camion;
/**
 * @Route("/sigcer")
 */

class GestionOpcionesController extends Controller
{

	////////////////Pais Origen//////////////////////////////////
    /**
     * @Route("/addctr", name="sigcer_add_pais_origen")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addPaisOrigen()
    {
        $em = $this->getDoctrine()->getManager();
        $paises = $em->getRepository(PaisDestino::class)->findAll();

        $pais = new PaisDestino();
        $formInf = $this->createForm(\GestionSigcerBundle\Form\opciones\PaisDestinoType::class, 
                                      $pais, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_pais_origen_procesar')]);
        return $this->render('@GestionSigcer/opciones/abmPaisOrigen.html.twig', 
                            ['form' => $formInf->createView(), 'paises' => $paises]);
    }

    /**
     * @Route("/addctrpr", name="sigcer_add_pais_origen_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarPaisOrigen(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paises = $em->getRepository(PaisDestino::class)->findAll();

        $pais = new PaisDestino();
        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\PaisDestinoType::class, 
                                      $pais, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_pais_origen_procesar')]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pais);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Pais origen almacenad exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_pais_origen');
        }
        return $this->render('@GestionSigcer/opciones/abmPaisOrigen.html.twig', 
                            ['form' => $form->createView(), 'paises' => $paises]);
    }


    ////////////////////////////Envase/////////////////////////////////////////////
    /**
     * @Route("/addenv", name="sigcer_add_envase")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEnvase()
    {
        $em = $this->getDoctrine()->getManager();
        $envases = $em->getRepository(Envase::class)->findAll();

        $envase = new Envase();
        $formInf = $this->createForm(\GestionSigcerBundle\Form\opciones\EnvaseType::class, 
                                      $envase, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_envase_procesar')]);
        return $this->render('@GestionSigcer/opciones/abmEnvase.html.twig', 
                            ['form' => $formInf->createView(), 'envases' => $envases]);
    }

    /**
     * @Route("/addenvpr", name="sigcer_add_envase_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarEnvase(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $envases = $em->getRepository(Envase::class)->findAll();

        $envase = new Envase();
        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\EnvaseType::class, 
                                      $envase, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_envase_procesar')]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($envase);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Envase almacenado exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_envase');
        }
        return $this->render('@GestionSigcer/opciones/abmEnvase.html.twig', 
                            ['form' => $form->createView(), 'paises' => $paises]);
    }

    ////////////////////////////Cliente/////////////////////////////////////////////
    /**
     * @Route("/addcli", name="sigcer_add_cliente")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addCliente()
    {
        $em = $this->getDoctrine()->getManager();
        $clientes = $em->getRepository(Cliente::class)->findAll();

        $cliente = new Cliente();
        $formInf = $this->createForm(\GestionSigcerBundle\Form\opciones\ClienteType::class, 
                                      $cliente, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_cliente_procesar')]);
        return $this->render('@GestionSigcer/opciones/abmCliente.html.twig', 
                            ['form' => $formInf->createView(), 'clientes' => $clientes]);
    }

    /**
     * @Route("/addclipr", name="sigcer_add_cliente_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarCliente(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $clientes = $em->getRepository(Cliente::class)->findAll();

        $cliente = new Cliente();
        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\ClienteType::class, 
                                      $cliente, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_cliente_procesar')]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cliente);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Cliente almacenado exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_cliente');
        }
        return $this->render('@GestionSigcer/opciones/abmCliente.html.twig', 
                            ['form' => $form->createView(), 'clientes' => $clientes]);
    }

    ////////////////////////////Camion/////////////////////////////////////////////
    /**
     * @Route("/addcmo", name="sigcer_add_camion")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addCamion()
    {
        $em = $this->getDoctrine()->getManager();
        $camiones = $em->getRepository(Camion::class)->findAll();

        $camion = new Camion();
        $formInf = $this->createForm(\GestionSigcerBundle\Form\opciones\CamionType::class, 
                                      $camion, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_camion_procesar')]);
        return $this->render('@GestionSigcer/opciones/abmCamion.html.twig', 
                            ['form' => $formInf->createView(), 'camiones' => $camiones]);
    }

    /**
     * @Route("/addcmprc", name="sigcer_add_camion_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarCamion(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $camiones = $em->getRepository(Camion::class)->findAll();

        $camion = new Camion();
        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\CamionType::class, 
                                      $camion, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_camion_procesar')]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($camion);
            $entityManager->flush();
            $this->addFlash(
                                'sussecc',
                                'Camion almacenado exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_camion');
        }
        return $this->render('@GestionSigcer/opciones/abmCamion.html.twig', 
                            ['form' => $form->createView(), 'camiones' => $camiones]);
    }
}
