<?php

namespace GestionFaenaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use GestionFaenaBundle\Form\gestionBD\ArticuloType;
use GestionFaenaBundle\Entity\gestionBD\Articulo;
use GestionFaenaBundle\Form\gestionBD\ArticuloProcesoFaenaType;
use GestionFaenaBundle\Form\gestionBD\AtributoAbstractoType;
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use GestionFaenaBundle\Form\gestionBD\AtributoMedibleManualType;
use GestionFaenaBundle\Entity\gestionBD\AtributoMedibleManual;
use GestionFaenaBundle\Entity\gestionBD\UnidadMedida;
use GestionFaenaBundle\Form\gestionBD\AtributoMedibleAutomaticoType;
use GestionFaenaBundle\Entity\gestionBD\AtributoMedibleAutomatico;
use GestionFaenaBundle\Form\gestionBD\AtributoInformableExternoType;
use GestionFaenaBundle\Form\gestionBD\AtributoInformableArbitrarioType;
use GestionFaenaBundle\Entity\gestionBD\AtributoInformableExterno;
use GestionFaenaBundle\Entity\gestionBD\AtributoInformableArbitrario;
use GestionFaenaBundle\Entity\gestionBD\Atributo;
use GestionFaenaBundle\Form\ProcesoInicioType;
use GestionFaenaBundle\Form\ProcesoMedioType;
use GestionFaenaBundle\Form\ProcesoFinType;
use GestionFaenaBundle\Entity\ProcesoInicio;
use GestionFaenaBundle\Entity\ProcesoMedio;
use GestionFaenaBundle\Entity\ProcesoFin;
use GestionFaenaBundle\Entity\ProcesoFaena;
use GestionFaenaBundle\Entity\faena\AtributoConcepto;
use GestionFaenaBundle\Form\faena\AtributoConceptoType;
use GestionFaenaBundle\Form\faena\ConceptoMovimientoProcesoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use GestionFaenaBundle\Repository\ProcesoFaenaRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints as Assert;
use GestionFaenaBundle\Form\gestionBD\AtrProcType;
use GestionFaenaBundle\Form\gestionBD\EntidadExternaType;
use GestionFaenaBundle\Form\gestionBD\UnidadMedidaType;
use GestionFaenaBundle\Entity\gestionBD\AtributoProceso;
use GestionFaenaBundle\Entity\gestionBD\AtributoAbstracto;
use GestionFaenaBundle\Entity\gestionBD\FactorCalculo;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use GestionFaenaBundle\Entity\faena\ConceptoMovimientoProceso;
use GestionFaenaBundle\Entity\gestionBD\ArticuloAtributoConcepto;
use GestionFaenaBundle\Form\gestionBD\ArticuloAtributoConceptoType;
use Symfony\Component\Validator\Constraints\NotNull;
use GestionFaenaBundle\Repository\gestionBD\ArticuloAtributoConceptoRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class GestionBDController extends Controller
{

    /**
     * @Route("/", name="main_server_root_site")

     */
    public function defaultServerAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('@GestionFaena/Default/success.html.twig');
    }


    ////////////////////////ALTA ARTICULO ATRIBUTO CONCEPTO
    /**
     * @Route("/addartatcon", name="bd_add_articulo_atributo_concepto")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addArticuloAtributoConcepto()
    {
        $articulo = new ArticuloAtributoConcepto();
        $form = $this->getFormArtAtrConcepto($articulo);
        return $this->render('@GestionFaena/gestionBD/articuloAtributoConceptoAlta.html.twig', array('form' => $form->createView()));
    }

    private function getFormArtAtrConcepto($articulo)
    {
        return $this->createForm(ArticuloAtributoConceptoType::class, $articulo, ['action' => $this->generateUrl('bd_add_articulo_atributo_concepto_procesar'),'method' => 'POST']);
    }

    /**
     * @Route("/addartatconproc", name="bd_add_articulo_atributo_concepto_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormArtAtrConcepto(Request $request)
    {
        $articulo = new ArticuloAtributoConcepto();
        $form = $this->getFormArtAtrConcepto($articulo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $articulo->updatePropiedades();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articulo);
            $entityManager->flush();
            return $this->redirectToRoute('bd_add_atributo', array('art' => $articulo->getId()));
        }
        return $this->render('@GestionFaena/gestionBD/articuloAtributoConceptoAlta.html.twig', array('form' => $form->createView()));
    }
    /////////////////FIN///////////////////////////////////
    ///////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////



    /////////////////Alta Atributo Abstracto
    /**
     * @Route("/addatrabs", name="bd_add_atributo_abstracto")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAtributoAbstracto()
    {
        $atr = new AtributoAbstracto();
        $form = $this->getFormAltaAtrAbs($atr);
        return $this->render('@GestionFaena/gestionBD/atributoAbstractoAlta.html.twig', array('form' => $form->createView()));
    }

    private function getFormAltaAtrAbs($atrabs)
    {
        return $this->createForm(AtributoAbstractoType::class, $atrabs, ['action' => $this->generateUrl('bd_add_atributo_abstracto_procesar'),'method' => 'POST']);
    }

    /**
     * @Route("/addatrabsproc", name="bd_add_atributo_abstracto_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoAbstracto(Request $request)
    {
        $atrabs = new AtributoAbstracto();
        $form = $this->getFormAltaAtrAbs($atrabs);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($atrabs);
            $entityManager->flush();
            $this->addFlash(
                        'sussecc',
                        'Atributo almacenado exitosamente!'
                    );
            return $this->redirectToRoute('bd_add_atributo_abstracto');
        }
        return $this->render('@GestionFaena/gestionBD/AtributoAbstractoAlta.html.twig', array('form' => $form->createView()));
    }
    //////////////////Fin Alta Atributo Abstracto

    /////////////////ABM Concepto movimiento proceso
    /**
     * @Route("/addcnmvpr", name="bd_add_concepto_movimiento_proceso")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addConceptoMovimientoProceso()
    {
        $concepto = new ConceptoMovimientoProceso();
        $form = $this->getFormAltaConceptoMovimientoProceso($concepto, $this->generateUrl('bd_add_concepto_movimiento_proceso_procesar'));
        return $this->render('@GestionFaena/gestionBD/conceptoMovimientoProcesoAlta.html.twig', array('form' => $form->createView()));
    }

    private function getFormAltaConceptoMovimientoProceso($concepto, $route)
    {
        return $this->createForm(ConceptoMovimientoProcesoType::class, $concepto, ['action' => $route,'method' => 'POST']);
    }

    /**
     * @Route("/addcnmvprproc", name="bd_add_concepto_movimiento_proceso_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioConceptoMovimientoProceso(Request $request)
    {
        $concepto = new ConceptoMovimientoProceso();
        $form = $this->getFormAltaConceptoMovimientoProceso($concepto, $this->generateUrl('bd_add_concepto_movimiento_proceso_procesar'));
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($concepto);
            $entityManager->flush();
            $this->addFlash(
                        'sussecc',
                        'Concepto de movimiento asociado exitosamente al proceso!'
                    );
            return $this->redirectToRoute('bd_add_concepto_movimiento_proceso');
        }
        return $this->render('@GestionFaena/gestionBD/conceptoMovimientoProcesoAlta.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/viewcnmvpr", name="bd_view_concepto_movimiento_proceso", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function viewConceptoMovimientoProceso(Request $request)
    {
        $form = $this->getFormSelectConceptosMovimientos();
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $data = $form->getData();
            return $this->render('@GestionFaena/gestionBD/conceptoMovimientoProcesoLista.html.twig', array('proc' => $data['proceso'], 'form' => $form->createView()));
        }

        return $this->render('@GestionFaena/gestionBD/conceptoMovimientoProcesoLista.html.twig', array('form' => $form->createView()));
    }

    private function getFormSelectConceptosMovimientos()
    {
        $form =    $this->createFormBuilder()
                        ->add('proceso', EntityType::class, [
                              'class' => 'GestionFaenaBundle:ProcesoFaena'
                        ])
                        ->add('guardar', SubmitType::class)         
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/editcnmvpr/{id}", name="bd_edit_concepto_movimiento_proceso")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editConceptoMovimientoProceso($id)
    {
        $concepto = $this->getDoctrine()->getManager()->find(ConceptoMovimientoProceso::class, $id);
        $form = $this->getFormAltaConceptoMovimientoProceso($concepto, $this->generateUrl('bd_edit_concepto_movimiento_proceso_procesar', array('id' => $id)));
        return $this->render('@GestionFaena/gestionBD/conceptoMovimientoProcesoAlta.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/editcnmvproc/{id}", name="bd_edit_concepto_movimiento_proceso_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editConceptoMovimientoProcesoProcesar($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $concepto = $em->find(ConceptoMovimientoProceso::class, $id);
        $form = $this->getFormAltaConceptoMovimientoProceso($concepto, $this->generateUrl('bd_edit_concepto_movimiento_proceso_procesar', array('id' => $id)));
        $form->handleRequest($request);
        if ($form->isValid()){
            $em->flush();
            return $this->redirectToRoute('bd_view_concepto_movimiento_proceso');
        }
        return $this->render('@GestionFaena/gestionBD/conceptoMovimientoProcesoAlta.html.twig', array('form' => $form->createView()));
    }
    //////////////////Fin ABM Concepto movimiento proceso



    ///////////////UNIDAD MEDIDA
    /**
     * @Route("/addUM", name="bd_add_unidad_medida")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addUnidadMedidaAction()
    {
        $unidad = new UnidadMedida();
        $form = $this->getFormABMUnidadMedida($unidad);
        return $this->render('@GestionFaena/gestionBD/unidadMedidaABM.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/addUMProc", name="bd_add_um_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioUnidadMedida(Request $request)
    {
        $unidad = new UnidadMedida();
        $form = $this->getFormABMUnidadMedida($unidad);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($unidad);
            $entityManager->flush();
            $this->addFlash(
                        'sussecc',
                        'Unidad de medida almacenada exitosamente!'
                    );
            return $this->redirectToRoute('bd_add_unidad_medida');
        }
        return $this->render('@GestionFaena/gestionBD/unidadMedidaABM.html.twig', array('form' => $form->createView()));
    }

    private function getFormABMUnidadMedida($unidad)
    {
        return $this->createForm(UnidadMedidaType::class, $unidad, ['action' => $this->generateUrl('bd_add_um_procesar'),'method' => 'POST']);
    }
    ///////////////////////////


    
    /**
     * @Route("/addArt", name="bd_add_articulo")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addArticuloAction()
    {
        $articulo = new Articulo();
        $form = $this->getFormABMArticulo($articulo);
        return $this->render('@GestionFaena/gestionBD/articuloABM.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/addArtProc", name="bd_add_articulo_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioArticulo(Request $request)
    {
        $articulo = new Articulo();
        $form = $this->getFormABMArticulo($articulo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articulo);
            $entityManager->flush();
            $this->addFlash(
                        'sussecc',
                        'Articulo almacenado exitosamente!'
                    );
            return $this->redirectToRoute('bd_add_articulo');
        }
        return $this->render('@GestionFaena/gestionBD/articuloABM.html.twig', array('form' => $form->createView()));
    }

    private function getFormABMArticulo($articulo)
    {
        return $this->createForm(ArticuloType::class, $articulo, ['action' => $this->generateUrl('bd_add_articulo_procesar'),'method' => 'POST']);
    }

    /**
     * @Route("/addArtProcFan", name="bd_add_articulo_proceso_faena")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addArticuloProcesoFaenaAction()
    {
        $articulo = new ArticuloProcesoFaena();
        $form = $this->getFormABMArticuloProcesoFan($articulo);
        return $this->render('@GestionFaena/gestionBD/articuloProcFanABM.html.twig', array('form' => $form->createView()));
    }

    private function getFormABMArticuloProcesoFan($articulo)
    {
        return $this->createForm(ArticuloProcesoFaenaType::class, $articulo, ['action' => $this->generateUrl('bd_add_articulo_proceso_faena_procesar'),'method' => 'POST']);
    }

    /**
     * @Route("/addArtProcFanProc", name="bd_add_articulo_proceso_faena_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioArticuloProcesoFaena(Request $request)
    {
        $articulo = new ArticuloProcesoFaena();
        $form = $this->getFormABMArticuloProcesoFan($articulo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articulo);
            $entityManager->flush();
            return $this->redirectToRoute('bd_add_articulo_proceso_faena');
        }
        return $this->render('@GestionFaena/gestionBD/articuloProcFanABM.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/vieAllAtributes", name="bd_atributos_view_all")
     */
    public function viewAllAtributes()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('GestionFaenaBundle:gestionBD\Atributo');
        $atributos = $repository->findAll();
        return $this->render('@GestionFaena/gestionBD/viewAllAtributes.html.twig', array('atributos' => $atributos));
    }

    /**
     * @Route("/vieAtribute/{atr}", name="bd_atributos_show", methods={"POST", "GET"})
     */
    public function showAtribute($atr, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('GestionFaenaBundle:gestionBD\Atributo');
        $atributo = $repository->find($atr);
        if ($atributo->getClass() == 4){
            $form = $this->createForm(AtributoMedibleAutomaticoType::class, $atributo, ['action' => $this->generateUrl('bd_atributos_show', array('atr' => $atr)) ,'method' => 'POST']);
            if ($request->isMethod('post')){
                $form->handleRequest($request);
                $entityManager->flush();
            }
            return $this->render('@GestionFaena/gestionBD/viewAtribute.html.twig', array('form' => $form->createView()));

        }
        return $this->render('@GestionFaena/gestionBD/viewAllAtributes.html.twig', array('atributos' => $atributo));
    }


    /**
     * @Route("/deleteArt/{art}", name="bd_delete_articulo", methods={"POST"})
     */
    public function deleteArticulo($art)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $atributo = $entityManager->find('GestionFaenaBundle:gestionBD\ArticuloAtributoConcepto', $art);
        try{
            $entityManager->remove($atributo->getConcepto());
            $entityManager->remove($atributo);
            $entityManager->flush();
            return new JsonResponse(array('ok'=>true, 'msge' => ''));
        }
        catch(\Exception $e){return new JsonResponse(array('msge'=>$e->getMessage()));}
    }


    /**
     * @Route("/editart", name="bd_editar_articulo")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarArticuloAction(Request $request)
    {        
        $form =    $this->createFormBuilder()
                        ->add('articulos', 
                              EntityType::class, [
                              'class' => 'GestionFaenaBundle:gestionBD\ArticuloAtributoConcepto',
                              'query_builder' => function (ArticuloAtributoConceptoRepository $er){
                                                                                        return $er->createQueryBuilder('a')
                                                                                                  ->innerJoin('a.articulo', 'art')
                                                                                                  ->innerJoin('a.concepto', 'con')
                                                                                                  ->innerJoin('con.procesoFaena', 'pfa')
                                                                                                  ->innerJoin('con.concepto', 'cmf')
                                                                                                  ->where('a.activo = :activo')   
                                                                                                  ->andWhere('con.automatico = :auto')      
                                                                                                  ->setParameter('activo', true)
                                                                                                  ->setParameter('auto', false)
                                                                                                  ->orderBy('pfa.nombre, cmf.concepto, art.nombre');
                                                                                                 },
                                'choice_label' => 'vistaEdicion',
                        ])
                        ->add('modificar', SubmitType::class, array('label' => 'Modificar atributos'))    
                        ->add('agregar', SubmitType::class, array('label' => 'Agregar atributos'))               
                        ->getForm();
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $data = $form->getData();
            if ($form->getClickedButton() === $form->get('agregar'))
            {
                return $this->redirectToRoute('bd_add_atributo', array('art' => $data['articulos']->getId()));
            }
            else 
            {
                $articulo = $data['articulos'];
                $forms = array();
                foreach ($articulo->getAtributos() as $atr) 
                {
                    $forms[$atr->getId()] = $this->getFormUpdateAtributo($atr)->createView();                    
                }
                return $this->render('@GestionFaena/gestionBD/atributoABMV2Update.html.twig', array('art' => $articulo, 'formsAtr' => $forms));
            }
        }
        return $this->render('@GestionFaena/gestionBD/atributoABMV2.html.twig', array('form' => $form->createView()));
    }

    private function getFormUpdateAtributo($atr)
    {        
        $form =    $this->createFormBuilder(['message' => 'Type your message here'])
                        ->add('posicion', IntegerType::class, ['data' => $atr->getPosicion()])
                        ->add('mostrar', CheckboxType::class, ['data' => $atr->getMostrar()])
                        ->add('atributoBase', EntityType::class, ['class' => AtributoAbstracto::class, 'data' => $atr->getAtributoBase()])
                        ->add('espejo', CheckboxType::class, ['data' => $atr->getEspejo()])
                        ->add('mostrarAlCargar', CheckboxType::class, ['data' => $atr->getMostrarAlCargar()])
                        ->add('decimales', IntegerType::class, ['data' => $atr->getDecimales(), 'disabled' => (!$atr->manejaDecimales())])
                        ->add('acumula', CheckboxType::class, ['label' => null, 'data' => $atr->getAcumula(), 'disabled' => (!$atr->manejaDecimales())])   
                        ->add('promedia', CheckboxType::class, ['data' => $atr->getPromedia(), 'disabled' => (!$atr->manejaDecimales())])   
                        ->add('guardar', SubmitType::class, array('label' => '+'))          
                        ->add('delete', ButtonType::class, array('label' => '-'))    
                        ->setAction($this->generateUrl('bd_update_atributo', array('atr' => $atr->getId())))            
                        ->getForm();
        return $form;
    }


    /**
     * @Route("/delatr/{atr}", name="bd_delete_atributo")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteAtributo($atr)
    {
        try{
                $em = $this->getDoctrine()->getManager();
                $atributo = $em->find(Atributo::class, $atr);
                $atributo->setEliminado(true);
                $atributo->setActivo(false);
                $em->flush();
                return new JsonResponse(array('ok' => true, 'message' => ''));
            }            
            catch(\Exception $e) {return new JsonResponse(array('ok' => false, 'message' => $e->getMessage()));}
    }

    /**
     * @Route("/updatr/{atr}", name="bd_update_atributo")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function updateAtributo($atr, Request $request)
    {
        try{
        $em = $this->getDoctrine()->getManager();
        $atributo = $em->find(Atributo::class, $atr);

        $form = $this->getFormUpdateAtributo($atributo);
        $form->handleRequest($request);
        $data = $form->getData();
        $atributo->setPosicion($data['posicion']);
        $atributo->setMostrar($data['mostrar']);
        $atributo->setMostrarAlCargar($data['mostrarAlCargar']);
        $atributo->setAtributoBase($data['atributoBase']);
        $atributo->setEspejo($data['espejo']);
        if ($atributo->manejaDecimales())
        {            
            $atributo->setDecimales($data['decimales']);
            $atributo->setAcumula($data['acumula']);
            $atributo->setPromedia($data['promedia']);
        }
        $em->flush();
        return new JsonResponse(array('posicion' => $data['posicion']));
    }
    catch(\Exception $e) {return new JsonResponse(array('posicion' => $e->getMessage()));}
    }


    private function getFormSetArticuloOrigen($concMovProc)
    {
        $form =    $this->createFormBuilder()
                        ->add('articulo', 
                              EntityType::class, [
                              'class' => 'GestionFaenaBundle:gestionBD\Articulo',
                              'choices' => $concMovProc->getProcesoFaena()->getArticulosStock(),
                        ])
                        ->add('agregar', SubmitType::class, array('label' => 'Agregar atributos')) 
                        ->setAction($this->generateUrl('bd_asignar_articulo_origen_transformacion', ['conc' => $concMovProc->getId()]))
                        ->setMethod('POST')              
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/assartor/{conc}", name="bd_asignar_articulo_origen_transformacion")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function asignarArticuloOrigenTransformacion($conc, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $concepto = $em->find(ConceptoMovimientoProceso::class, $conc);
        $form = $this->getFormSetArticuloOrigen($concepto);
        $form->handleRequest($request);
        if ($form->isValid()){
            $data = $form->getData();
            $concepto->setArticuloOrigenTransformacion($data['articulo']);
            $em->flush();
            return new JsonResponse(['status' => true]);
        }
    }

    /**
     * @Route("/addatr/{art}", name="bd_add_atributo")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAtributoMedibleManualAction($art)
    {
        if ($art){
            $em = $this->getDoctrine()->getManager();
            $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        }
        $formAMM = $this->getFormABMAtributoMedibleManual(new AtributoMedibleManual(), $articulo);
        $formAMA = $this->getFormABMAtributoMedibleAutomatico(new AtributoMedibleAutomatico(), $articulo);
        $formAIE = $this->getFormABMAtributoInformableExterno(new AtributoInformableExterno(), $articulo);
        $formAIM = $this->getFormABMAtributoInformableManual(new AtributoInformableArbitrario(), $articulo);
        if ($art)
        {
            if ($articulo->getConcepto()->getTipoMovimiento()->getTransformaProductos()){
                $trans = $this->getFormSetArticuloOrigen($articulo->getConcepto());
                return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('transf' => $trans->createView(), 'articulo' => $articulo, 'formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
            }
            return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('articulo' => $articulo, 'formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
        }
            
        
        /*return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));*/
    }

    private function getFormABMAtributoMedibleManual($atributo, $art)
    {
        return $this->createForm(AtributoMedibleManualType::class, $atributo, ['action' => $this->generateUrl('bd_add_tributo_medible_manual_procesar', array('art' => $art->getId())),'method' => 'POST']);
    }

    private function getFormABMAtributoMedibleAutomatico($atributo, $art)
    {
        return $this->createForm(AtributoMedibleAutomaticoType::class, $atributo, ['articulo' => $art, 'action' => $this->generateUrl('bd_add_tributo_medible_automatico_procesar', array('art' => $art->getId())),'method' => 'POST']);
    }

    private function getFormABMAtributoInformableExterno($atributo, $art)
    {
        return $this->createForm(AtributoInformableExternoType::class, $atributo, ['action' => $this->generateUrl('bd_add_tributo_informable_procesar', array('art' => $art->getId())),'method' => 'POST']);
    }

    private function getFormABMAtributoInformableManual($atributo, $art)
    {
        return $this->createForm(AtributoInformableArbitrarioType::class, $atributo, ['action' => $this->generateUrl('bd_add_tributo_informable_manual_procesar', array('art' => $art->getId())),'method' => 'POST']);
    }

    /**
     * @Route("/addAIMProc/{art}", name="bd_add_tributo_informable_manual_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoInformableManual($art, Request $request)
    {
        if ($art){
            $em = $this->getDoctrine()->getManager();
            $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        }
        $atributo = new AtributoInformableArbitrario();
        $formAIM = $this->getFormABMAtributoInformableManual($atributo, $articulo);
        $formAIM->handleRequest($request);
        if ($formAIM->isValid())
        {
            if (!$articulo->existeAtributoActivo($atributo->getAtributoAbstracto()))
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($atributo);
                $atributo->setArticuloAtrConc($articulo);
                $entityManager->flush();
                return $this->redirectToRoute('bd_add_atributo', array('art' => $articulo->getId()));
            }
            $this->addFlash('errors','El atributo '.$atributo->getAtributoAbstracto().' ya se encuentra asociado al articulo!');
        }
        $formAMM = $this->getFormABMAtributoMedibleManual(new AtributoMedibleManual(), $articulo);
        $formAMA = $this->getFormABMAtributoMedibleAutomatico(new AtributoMedibleAutomatico(), $articulo);
        $formAIE = $this->getFormABMAtributoInformableExterno(new AtributoInformableExterno(), $articulo);
        if ($art)
            return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('articulo' => $articulo, 'formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
        return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
    }


    /**
     * @Route("/addAMMProc/{art}", name="bd_add_tributo_medible_manual_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoMedibleManual($art, Request $request)
    {
        if ($art){
            $em = $this->getDoctrine()->getManager();
            $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        }
        $atributo = new AtributoMedibleManual();
        $form = $this->getFormABMAtributoMedibleManual($atributo, $articulo);
        $formAMA = $this->getFormABMAtributoMedibleAutomatico(new AtributoMedibleAutomatico(), $articulo);
        $formAIE = $this->getFormABMAtributoInformableExterno(new AtributoInformableExterno(), $articulo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            if (!$articulo->existeAtributoActivo($atributo->getAtributoAbstracto()))
            {
                try{
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($atributo);
                    $atributo->setArticuloAtrConc($articulo);
                    $entityManager->flush();
                    return $this->redirectToRoute('bd_add_atributo', array('art' => $articulo->getId()));
                }
                catch (\Exception $e){
                        return  new Response($e->getMessage());
                }
            }
            $this->addFlash('errors','El atributo '.$atributo->getAtributoAbstracto().' ya se encuentra asociado al articulo!');
        }
        else
        {
           // return new Response('vanvivo '.$form->getErrors());
        }
        $formAIM = $this->getFormABMAtributoInformableManual(new AtributoInformableArbitrario(), $articulo);
       // if ($art)
            return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('articulo' => $articulo, 'formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $form->createView(), 'formAMA' => $formAMA->createView()));
        return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('form' => $form->createView(), 'formAMA' => $formAMA->createView()));
    }

    /**
     * @Route("/addAMAProc/{art}", name="bd_add_tributo_medible_automatico_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoMedibleAutomatico($art, Request $request)
    {
        if ($art){
            $em = $this->getDoctrine()->getManager();
            $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        }
        $form = $this->getFormABMAtributoMedibleManual(new AtributoMedibleManual(), $articulo);
        $atributo = new AtributoMedibleAutomatico();
        $formAMA = $this->getFormABMAtributoMedibleAutomatico($atributo, $articulo);
        $formAMA->handleRequest($request);
        if ($formAMA->isValid())
        {
            if (!$articulo->existeAtributoActivo($atributo->getAtributoAbstracto()))
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($atributo);
                $atributo->setArticuloAtrConc($articulo);
                $entityManager->flush();
                return $this->redirectToRoute('bd_add_atributo', array('art' => $articulo->getId()));
            }
            $this->addFlash('errors','El atributo '.$atributo->getAtributoAbstracto().' ya se encuentra asociado al articulo!');
        }
        $formAIE = $this->getFormABMAtributoInformableExterno(new AtributoInformableExterno(), $articulo);
        $formAIM = $this->getFormABMAtributoInformableManual(new AtributoInformableArbitrario(), $articulo);
        if ($art)
            return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('articulo' => $articulo, 'formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $form->createView(), 'formAMA' => $formAMA->createView()));
        //return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('form' => $form->createView(), 'formAMA' => $formAMA->createView()));
    }

    /**
     * @Route("/addAIProc/{art}", name="bd_add_tributo_informable_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoInformable($art, Request $request)
    {
        if ($art){
            $em = $this->getDoctrine()->getManager();
            $articulo = $em->find(ArticuloAtributoConcepto::class, $art);
        }
        $formAMM = $this->getFormABMAtributoMedibleManual(new AtributoMedibleManual(), $articulo);
        $formAMA = $this->getFormABMAtributoMedibleAutomatico(new AtributoMedibleAutomatico(), $articulo);
        $formAIM = $this->getFormABMAtributoInformableManual(new AtributoInformableArbitrario(), $articulo);
        $atributo = new AtributoInformableExterno();
        $form = $this->getFormABMAtributoInformableExterno($atributo, $articulo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            if (!$articulo->existeAtributoActivo($atributo->getAtributoAbstracto()))
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($atributo);
                $atributo->setArticuloAtrConc($articulo);
                $entityManager->flush();
                return $this->redirectToRoute('bd_add_atributo', array('art' => $articulo->getId()));
            }
            $this->addFlash('errors','El atributo '.$atributo->getAtributoAbstracto().' ya se encuentra asociado al articulo!');
        }
        
        if ($art)
            return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('articulo' => $articulo, 'formAIM' => $formAIM->createView(),'formAI' => $form->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
       // return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('formAI' => $form->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
    }

    //////////ADMINISTA LOS PROCESOS///////////////////////////
    /**
     * @Route("/addPrc/{type}", name="bd_add_proceso")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addProcesoAction($type)
    {
        if ($type == 1){
            $proceso = new ProcesoInicio();
            $typeForm = ProcesoInicioType::class;
            $label = "Nuevo Proceso inicio";
        }
        elseif ($type == 2) {
            $proceso = new ProcesoMedio();
            $typeForm = ProcesoMedioType::class;
            $label = "Nuevo Proceso Manufacturero";
        }
        elseif ($type == 3) {
            $proceso = new ProcesoFin();
            $typeForm = ProcesoFinType::class;
            $label = "Nuevo Proceso Fin";
        }
        $form = $this->getFormABMProceso($proceso, $typeForm, $type);
        return $this->render('@GestionFaena/procesoABM.html.twig', array('form' => $form->createView(), 'label' => $label));
    }

    private function getFormABMProceso($proceso, $form, $type)
    {
        return $this->createForm($form, $proceso, ['action' => $this->generateUrl('bd_add_proceso_procesar', array('type' => $type)),'method' => 'POST']);
    }

    /**
     * @Route("/addProcProc/{type}", name="bd_add_proceso_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioProceso($type, Request $request)
    {
        if ($type == 1){
            $proceso = new ProcesoInicio();
            $typeForm = ProcesoInicioType::class;
            $label = "Nuevo Proceso inicio";
        }
        elseif ($type == 2) {
            $proceso = new ProcesoMedio();
            $typeForm = ProcesoMedioType::class;
            $label = "Nuevo Proceso Manufacturero";
        }
        elseif ($type == 3) {
            $proceso = new ProcesoFin();
            $typeForm = ProcesoFinType::class;
            $label = "Nuevo Proceso Fin";
        }
        $form = $this->getFormABMProceso($proceso, $typeForm, $type);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proceso);
            $entityManager->flush();
            return $this->redirectToRoute('bd_add_proceso', array('type' => $type));
        }
        return $this->render('@GestionFaena/procesoABM.html.twig', array('form' => $form->createView(), 'label' => $label));
    }

    /**
     * @Route("/viewProc", name="bd_view_procesos")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function viewProcesosAction()
    {
        $procesos = $this->getDoctrine()->getRepository(ProcesoFaena::class)->findAllProcesos();
        return $this->render('@GestionFaena/procesoView.html.twig', array('procesos' => $procesos));
    }

    /**
     * @Route("/editProc/{proccess}", name="bd_edit_procesos")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editProcesosAction($proccess)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $proceso = $entityManager->find(ProcesoFaena::class, $proccess);
        return $this->render('@GestionFaena/procesoEdit.html.twig', 
                            array('proccess' => $proceso, 
                                  'form' => $this->getFormAddDestinoProceso($proccess)->createView(),
                                  'stock' => $this->getFormConfigurarManejoStock($proceso)->createView()));
    }

    /**
     * @Route("/deletDest/{origen}/{destino}", name="delete_destination")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteDestination($origen, $destino)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $origen = $entityManager->find(ProcesoFaena::class, $origen);
        $destino = $entityManager->find(ProcesoFaena::class, $destino);
        $origen->removeProcesosDestino($destino);
        $entityManager->flush();
        return $this->redirectToRoute('bd_edit_procesos', ['proccess' => $origen]);
    }


    /**
     * @Route("/updmnjst/{proc}", name="bd_update_manejo_stock", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function updateManejoStockProceso($proc, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $proceso = $entityManager->find(ProcesoFaena::class, $proc);
        $form = $this->getFormConfigurarManejoStock($proceso);
        $form->handleRequest($request);
        if ($form->isValid()){
            $data = $form->getData();
            $factor = new FactorCalculo();
            $factor->setAtributo($data['atributo']);
            $factor->setArticulo($data['articulo']);
            $entityManager->persist($factor);
            $proceso->addManejosStock($factor);
            $entityManager->flush();
            return $this->redirectToRoute('bd_edit_procesos', ['proccess' => $proc]);
        }

    }

    private function getFormConfigurarManejoStock($proceso)
    {
        $form =    $this->createFormBuilder()
                        ->add('articulo', EntityType::class, [
                              'class' => 'GestionFaenaBundle:gestionBD\Articulo',                              
          
                        ])
                        ->add('atributo', EntityType::class, [
                              'class' => 'GestionFaenaBundle:gestionBD\AtributoAbstracto',
                             
                              'query_builder' => function (EntityRepository $er){
                                                                                        return $er->createQueryBuilder('u')
                                                                                                  ->join('u.atributos', 'a')
                                                                                                  ->where('a INSTANCE OF GestionFaenaBundle\Entity\gestionBD\AtributoMedibleManual OR a INSTANCE OF GestionFaenaBundle\Entity\gestionBD\AtributoMedibleAutomatico');
                            }
                        ])
                        ->add('asignar', SubmitType::class)    
                        ->setAction($this->generateUrl('bd_update_manejo_stock', array('proc' => $proceso->getId())))  
                        ->setMethod('POST')               
                        ->getForm();
        return $form;
    }

    private function getFormAddDestinoProceso($proceso)
    {
        $form =    $this->createFormBuilder()
                        ->add('destination', EntityType::class, [
                              'class' => 'GestionFaenaBundle:ProcesoFaena',
                              'query_builder' => function (ProcesoFaenaRepository $er) use ($proceso){
                                                                                        return $er->createQueryBuilder('u')
                                                                                                  ->where('u.id <> :id')
                                                                                                  ->setParameter('id', $proceso)
                                                                                                  ->orderBy('u.nombre', 'ASC');
                            }
                        ])
                        ->add('guardar', SubmitType::class)    
                        ->setAction($this->generateUrl('bd_edit_procesos_add_sender', array('proccess' => $proceso)))                 
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/addSender/{proccess}", name="bd_edit_procesos_add_sender")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editProcesosProcesarAction($proccess, Request $request)
    {
        $form = $this->getFormAddDestinoProceso($proccess);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $proceso = $entityManager->find(ProcesoFaena::class, $proccess);
                $data = $form->getData();
                $proceso->addProcesosDestino($data['destination']);
                $entityManager->flush();
                return  new JsonResponse(['status' => true]);
            }
            catch (\Exception $e){
                return  new JsonResponse(['status' => false, 'message' => $e->getMessage()]);
            }
        }
        return  new JsonResponse(['status' => false, 'message' => 'Datos incompletos!']);
    }

    /**
     * @Route("/delgstst/{proccess}/{manejo}", name="bd_delete_manejo_stock_from_proccess")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteManejoStockProccess($proccess, $manejo)
    {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $proceso = $entityManager->find(ProcesoFaena::class, $proccess);
                $stock = $entityManager->find(FactorCalculo::class, $manejo);
                $proceso->removeManejosStock($stock);
                $entityManager->flush();
            }
            catch (\Exception $e){

            }
            return $this->redirectToRoute('bd_edit_procesos', ['proccess' => $proccess]);
    }

    /**
     * @Route("/viewArtProcFan/{articulo}", name="bd_view_art_proc_fan")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function viewArticuloProcesoFaena($articulo)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $artprocfan = $entityManager->find(ArticuloProcesoFaena::class, $articulo);
        return $this->render('@GestionFaena/gestionBD/viewArtProcFan.html.twig', array('article' => $artprocfan, 'form' => $this->getFormAddNewAtributo($articulo)->createView()));
    }

    /**
     * @Route("/addAtrArtProcFan/{articulo}", name="bd_add_art_proc_fan", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAtrArtProcFanAction($articulo, Request $request)
    {
        $form = $this->getFormAddNewAtributo($articulo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            try
            {
                $entityManager = $this->getDoctrine()->getManager();
                $artprocfan = $entityManager->find(ArticuloProcesoFaena::class, $articulo);
                $data = $form->getData();
                $artprocfan->addAtributo($data['atributo']);
                $entityManager->flush();
                return  new JsonResponse(['status' => true]);
            }
            catch (\Exception $e){
                return  new JsonResponse(['status' => false, 'message' => $e->getMessage()]);
            }
        }
        return  new JsonResponse(['status' => false, 'message' => 'Datos incompletos!']);
    }

    private function getFormAddNewAtributo($articulo)
    {
        $form =    $this->createFormBuilder()
                        ->add('atributo', EntityType::class, [
                              'class' => 'GestionFaenaBundle:gestionBD\Atributo'
                        ])
                        ->add('articulo', HiddenType::class, ['data' => $articulo])
                        ->add('guardar', SubmitType::class)   
                        ->setAction($this->generateUrl('bd_add_art_proc_fan', array('articulo' => $articulo)))            
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/cnfAtrPrc", name="bd_config_atr_proceso")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editAtributoConceptoAction()
    {
        $atrCon = new AtributoConcepto();
        $form = $this->createForm(AtributoConceptoType::class, $atrCon, ['action' => $this->generateUrl('bd_config_atr_proceso_procesar'),'method' => 'POST']);
        return $this->render('@GestionFaena/faena/editConcAtr.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/cnfAtrPrcProcesar", name="bd_config_atr_proceso_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editAtributoConceptoProcesar(Request $request)
    {
        $atrCon = new AtributoConcepto();
        $form = $this->createForm(AtributoConceptoType::class, $atrCon, ['action' => $this->generateUrl('bd_config_atr_proceso_procesar'),'method' => 'POST']);
        $form->handleRequest($request);
        if ($form->isValid())
        {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($atrCon);
                $entityManager->flush();
                return  $this->redirectToRoute('bd_config_atr_proceso');
        }
        return $this->render('@GestionFaena/faena/editConcAtr.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/edConAtr", name="bd_editar_atr_concepto", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarAtributoConceptoProceso(Request $request){
        $form =    $this->createFormBuilder()
                        ->add('proceso', EntityType::class, [
                              'class' => 'GestionFaenaBundle:ProcesoFaena',
                              'required' => false,
                              'attr' =>['class' => 'proceso']
                        ])
                        ->add('conceptos', EntityType::class, [
                              'class' => 'GestionFaenaBundle:faena\ConceptoMovimiento',
                              'required' => false,
                              'attr' =>['class' => 'concepto']
                        ])
                        ->add('atrConcepto', EntityType::class, [
                              'class' => 'GestionFaenaBundle:faena\AtributoConcepto',
                              'required' => false,
                              'attr' =>['class' => 'atrCon'],
                              'constraints' => [new NotNull(array('message' => "Debe seleccionar un movimiento!!"))]
                        ])
                        ->add('guardar', SubmitType::class, ['label' => 'Siguiente >>'])
                         ->setMethod('POST')
                         ->getForm();
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isValid())
            return new Response("okaoakoakao");
            return $this->render('@GestionFaena/gestionBD/editAtrConProc.html.twig', array('form' => $form->createView()));
        }
        return $this->render('@GestionFaena/gestionBD/editAtrConProc.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/getConcProc/{proc}", name="bd_get_conceptos_proceso", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function getConceptosProceso($proc, Request $request){
        $em = $this->getDoctrine()->getManager();
        $proceso = $em->find(ProcesoFaena::class, $proc);
        $articulos = array();
        $articulos[] = '';
        foreach ($proceso->getConceptos() as $art) {
            $articulos[$art->getId()] = $art->getConcepto()->getConcepto();
        }
        return new JsonResponse($articulos);
    }

    /**
     * @Route("/getAtribCon/{con}", name="bd_get_atributos_concepto", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function getAtributosConceptosProceso($con, Request $request){
        $em = $this->getDoctrine()->getManager();
        $concepto = $em->find('GestionFaenaBundle:faena\ConceptoMovimientoProceso', $con);
        $articulos = array();
        $articulos[] = '';
        foreach ($concepto->getArticulos() as $art) {
            $articulos[$art->getId()] = $art."";
        }
        return new JsonResponse($articulos);
    }

    /**
     * @Route("/delAtPrAtCn/{atco}/{atpr}", name="bd_delete_atr_proc_atr_conc", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteAtributoProcesoOfAtributoConcepto($atco, $atpr, Request $request){
        try
        {
            $em = $this->getDoctrine()->getManager();
            $atrConcepto = $em->find('GestionFaenaBundle:faena\AtributoConcepto', $atco);
            $atrProceso = $em->find('GestionFaenaBundle:gestionBD\AtributoProceso', $atpr);
            $atrConcepto->removeAtributo($atrProceso);
            $em->flush();
            return new JsonResponse(array('status' => true));
        }
        catch (\Exception $e){return new JsonResponse(array('status' => false));}
    }


    /**
     * @Route("/getAtribAtribCon/{ac}", name="bd_get_atributos_atributo_concepto", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function getAtributosAtributoConcepto($ac, Request $request){
        $em = $this->getDoctrine()->getManager();
        $atributo = $em->find(AtributoConcepto::class, $ac);
        $form = $this->getFormAddAtrProcAtAtrCon($ac);
        return $this->render('@GestionFaena/gestionBD/viewAllAtributesAtributoConcepto.html.twig', array('form' => $form->createView(), 'atr' => $atributo));
    }

    /**
     * @Route("/addAtrPrAtrCn/{atrcon}", name="bd_add_atributo_proceso_a_atributo_concepto", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAtrProcAtAtrConc($atrcon, Request $request){
        try
        {
            $em = $this->getDoctrine()->getManager();
            $atrConcepto = $em->find(AtributoConcepto::class, $atrcon);
            $form = $this->getFormAddAtrProcAtAtrCon($atrcon);
            $form->handleRequest($request);
            $data = $form->getData();
            $atrConcepto->addAtributo($data['atributo']);
            $em->flush();
            return new JsonResponse(array('status' => true));
        }
        catch (\Exception $e) {return new JsonResponse(array('status' => false));}
    }

    private function getFormAddAtrProcAtAtrCon($atrcon)
    {
        $form =    $this->createFormBuilder()
                        ->add('atributo', EntityType::class, [
                              'class' => 'GestionFaenaBundle:gestionBD\AtributoProceso',
                        ])
                        ->add('add', SubmitType::class, ['label' => 'Agregar'])   
                        ->setAction($this->generateUrl('bd_add_atributo_proceso_a_atributo_concepto', array('atrcon' => $atrcon)))             
                        ->getForm();
        return $form;
    }
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////







    /**
     * @Route("/editAtrArtProc", name="bd_editar_atributo_articulo", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarAtributoProceso(Request $request){
        $form = $this->getFormSelectAtrArtProc();
        return $this->render('@GestionFaena/gestionBD/articuloProcFanEdit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/artProcChnage/{art}", name="bd_change_articulo_proceso", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function changeStateArticuloProceso($art, Request $request){
        $em = $this->getDoctrine()->getManager();
        $articulo = $em->find(ArticuloProcesoFaena::class, $art);
        $articulo->setActivo(!$articulo->getActivo());
        $em->flush();
    }



    /**
     * @Route("/editArtProc", name="bd_editar_articulo_proceso", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarArticuloProceso(Request $request){
        $form = $this->createFormBuilder()
                     ->add('proceso', EntityType::class, ['required' => true,'class' => 'GestionFaenaBundle:ProcesoFaena'])
                     ->add('cargar', SubmitType::class, ['label' => 'Cargar Articulos', 'attr' => ['class' => 'loadArt btn-primary ']])
                     ->setMethod('POST')
                     ->getForm();
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $data = $form->getData();
            $formAdd = array();
            foreach ($data['proceso']->getArticulos() as $art) {
                $formAdd[$art->getId()] = $this->getFormAddAtributoArticulo($art->getId())->createView();
            }
            return $this->render('@GestionFaena/gestionBD/editarArticuloProceso.html.twig', array('formsAdd' => $formAdd, 'proceso' => $data['proceso'], 'form' => $form->createView()));
        }
        return $this->render('@GestionFaena/gestionBD/editarArticuloProceso.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/addAtrProcP/{artic}", name="bd_add_atr_art_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function adAtrArtProcesar($artic, Request $request){
        $em = $this->getDoctrine()->getManager();
        $articulo = $em->find(ArticuloProcesoFaena::class, $artic);

        $form = $this->getFormAddAtributoArticulo($artic);
        $form->handleRequest($request);
        $data = $form->getData();
        $atr = new AtributoProceso();
        $atr->setAtributo($data['atributo']);
        $articulo->addAtributo($atr);
        $em->persist($atr);
        $em->flush();
    }

    private function getFormAddAtributoArticulo($articulo)
    {
        $form =    $this->createFormBuilder()
                        ->add('atributo', EntityType::class, [
                              'class' => 'GestionFaenaBundle:gestionBD\Atributo',
                        ])
                        ->add('add', SubmitType::class)   
                        ->setAction($this->generateUrl('bd_add_atr_art_procesar', array('artic' => $articulo)))             
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/editAtrConc", name="bd_editar_atributo_concepto", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarAtributoConcepto(Request $request){
        $form = $this->createFormBuilder()
                     ->add('concepto', EntityType::class, ['required' => true,'class' => 'GestionFaenaBundle:faena\ConceptoMovimiento'])
                     ->add('cargar', SubmitType::class, ['label' => 'Cargar Atributos', 'attr' => ['class' => 'loadArt btn-primary ']])
                     ->setMethod('POST')
                     ->getForm();
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $data = $form->getData();
            return $this->render('@GestionFaena/gestionBD/editarAtributoProceso.html.twig', array('concepto' => $data['concepto'], 'form' => $form->createView()));
        }
        return $this->render('@GestionFaena/gestionBD/editarAtributoProceso.html.twig', array('form' => $form->createView()));
    }

    private function getFormSelectAtrArtProc()
    {
        $form =    $this->createFormBuilder()
                        ->add('proceso', EntityType::class, [
                              'required' => false,
                              'class' => 'GestionFaenaBundle:ProcesoFaena',
                              'attr' => ['class' => 'proceso']
                        ])
                        ->add('articulos', EntityType::class, [
                              'required' => false,
                              'class' => 'GestionFaenaBundle:gestionBD\ArticuloProcesoFaena',
                              'choices' =>[],
                              'attr' => ['class' => 'articulo']
                        ])
                        ->add('atributos', EntityType::class, [
                              'required' => false,
                              'class' => 'GestionFaenaBundle:gestionBD\AtributoProceso',
                              'choices' =>[],
                              'attr' => ['class' => 'atributo']
                        ])  
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/getArtProc", name="bd_get_articles_for_proccess", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function getArticulosProceso(Request $request){
        $id = $request->query->get("prid");
        $entityManager = $this->getDoctrine()->getManager();
        $proceso = $entityManager->find('GestionFaenaBundle:ProcesoFaena', $id);
        $articulos = array();
        $articulos[] = array('key' => '', 'value' =>'');
        foreach ($proceso->getArticulos() as $articulo) {
            $articulos[] = array('key' => $articulo->getId(), 'value' => $articulo->getArticulo()->getNombre());
        }
        return new JsonResponse($articulos);
    }

    /**
     * @Route("/getAtrArt", name="bd_get_atributes_for_articles", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function getAtributosArticulos(Request $request){
        $id = $request->query->get("artid");
        $entityManager = $this->getDoctrine()->getManager();
        $articulo = $entityManager->find('GestionFaenaBundle:gestionBD\ArticuloProcesoFaena', $id);
        $atributos = array();
        $atributos[] = array('key' => '', 'value' =>'');
        foreach ($articulo->getAtributos() as $atributo) {
            $atributos[] = array('key' => $atributo->getId(), 'value' => $atributo->getAtributo()->getNombre());
        }
        return new JsonResponse($atributos);
    }

    /**
     * @Route("/getAtr", name="bd_get_atribute", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function getAtributo(Request $request){
        try
        {
            $id = $request->query->get("atrid");
            $entityManager = $this->getDoctrine()->getManager();
            $atributo = $entityManager->find('GestionFaenaBundle:gestionBD\AtributoProceso', $id);
            $form = $this->createForm(AtrProcType::class, $atributo, ['action' => $this->generateUrl('bd_editar_atributo', array('atr' => $id)),'method' => 'POST']);
            return $this->render('@GestionFaena/gestionBD/atrProc.html.twig', array('form' => $form->createView()));
        }
        catch (\Exception $e){ 
                                return new Response($e->getMessage());
                            };
    }

    /**
     * @Route("/editAtr/{atr}", name="bd_editar_atributo", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarAtributo($atr, Request $request){

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $atributo = $entityManager->find('GestionFaenaBundle:gestionBD\AtributoProceso', $atr);
            $form = $this->createForm(AtrProcType::class, $atributo, ['action' => $this->generateUrl('bd_editar_atributo', array('atr' => $atr)),'method' => 'POST']);
            $form->handleRequest($request);
            $entityManager->flush();
            return new JsonResponse(array('status' => true));
        } catch (\Exception $e) {
             return new JsonResponse(array('status' => false, 'msge' => $e->getMessage()));
        }

        //return $this->render('@GestionFaena/gestionBD/atrProc.html.twig', array('form' => $form->createView()));
    }
}
