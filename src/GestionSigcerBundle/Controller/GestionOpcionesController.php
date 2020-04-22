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
use GestionSigcerBundle\Entity\opciones\Producto;
use GestionSigcerBundle\Entity\opciones\Zona;

/**
 * @Route("/sigcer")
 */

class GestionOpcionesController extends Controller
{

  ///////////////ZONA//////////////////////////////////
    /**
     * @Route("/editzna/{zna}", name="sigcer_editar_zona")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editZona($zna)
    {
        $em = $this->getDoctrine()->getManager();
        $zona = $em->find(Zona::class, $zna);

        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\ZonaType::class, 
                                      $zona, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_editar_zona_procesar', ['zna' => $zona->getId()])]);
        return $this->render('@GestionSigcer/opciones/editZona.html.twig', 
                            ['form' => $form->createView()]);
    }

    /**
     * @Route("/editznaproc/{zna}", name="sigcer_editar_zona_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarEditZona($zna, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $zona = $em->find(Zona::class, $zna);

        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\ZonaType::class, 
                                      $zona, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_editar_zona_procesar', ['zna' => $zona->getId()])]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em->flush();
            $this->addFlash(
                                'sussecc',
                                'Zona modificada exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_zona');
        }
        return $this->render('@GestionSigcer/opciones/editZona.html.twig', 
                            ['form' => $form->createView()]);
    }

    /**
     * @Route("/addzna", name="sigcer_add_zona")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addZona()
    {
        $em = $this->getDoctrine()->getManager();
        $zonas = $em->getRepository(Zona::class)->findAll();

        $zona = new Zona();
        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\ZonaType::class, 
                                      $zona, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_zona_procesar')]);
        return $this->render('@GestionSigcer/opciones/abmZona.html.twig', 
                            ['form' => $form->createView(), 'zonas' => $zonas]);
    }

    /**
     * @Route("/addznaproc", name="sigcer_add_zona_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarZona(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $zonas = $em->getRepository(Zona::class)->findAll();

        $zona = new Zona();
        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\ZonaType::class, 
                                      $zona, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_zona_procesar')]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em->persist($zona);
            $em->flush();
            $this->addFlash(
                                'sussecc',
                                'Zona almacenada exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_zona');
        }
        return $this->render('@GestionSigcer/opciones/abmZona.html.twig', 
                            ['form' => $form->createView(), 'zonas' => $zonas]);
    }
  ///////////////Producto//////////////////////////////////
    /**
     * @Route("/addprd", name="sigcer_add_producto")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addProducto()
    {
        $em = $this->getDoctrine()->getManager();
        $productos = $em->getRepository(Producto::class)->findAll();

        $producto = new Producto();
        $formInf = $this->createForm(\GestionSigcerBundle\Form\opciones\ProductoType::class, 
                                      $producto, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_producto_procesar')]);
        return $this->render('@GestionSigcer/opciones/abmProducto.html.twig', 
                            ['form' => $formInf->createView(), 'productos' => $productos]);
    }

    /**
     * @Route("/addprdprc", name="sigcer_add_producto_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarProducto(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productos = $em->getRepository(Producto::class)->findAll();

        $producto = new Producto();
        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\ProductoType::class, 
                                      $producto, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_add_producto_procesar')]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em->persist($producto);
            $em->flush();
            $this->addFlash(
                                'sussecc',
                                'Producto almacenado exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_producto');
        }
        return $this->render('@GestionSigcer/opciones/abmProducto.html.twig', 
                            ['form' => $form->createView(), 'productos' => $productos]);
    }

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
     * @Route("/editcmo/{cmo}", name="sigcer_editar_camion")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarCamion($cmo)
    {
        $em = $this->getDoctrine()->getManager();
        $camion = $em->find(Camion::class, $cmo);

        $formInf = $this->createForm(\GestionSigcerBundle\Form\opciones\CamionType::class, 
                                      $camion, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_editar_camion_editar', ['cmo' => $camion->getId()])]);
        return $this->render('@GestionSigcer/opciones/editCamion.html.twig', 
                            ['form' => $formInf->createView()]);
    }

    /**
     * @Route("/editcmoproc/{cmo}", name="sigcer_editar_camion_editar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarEditarCamion($cmo, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $camion = $em->find(Camion::class, $cmo);

        $form = $this->createForm(\GestionSigcerBundle\Form\opciones\CamionType::class, 
                                      $camion, 
                                      ['method' => 'POST',
                                       'action' => $this->generateUrl('sigcer_editar_camion_editar', ['cmo' => $camion->getId()])]);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $em->flush();
            $this->addFlash(
                                'sussecc',
                                'Camion modificado exitosamente!'
                            );
            return $this->redirectToRoute('sigcer_add_camion');
        }
        return $this->render('@GestionSigcer/opciones/editCamion.html.twig', 
                            ['form' => $form->createView()]);
    }

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
