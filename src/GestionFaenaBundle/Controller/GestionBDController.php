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
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use GestionFaenaBundle\Form\gestionBD\AtributoMedibleManualType;
use GestionFaenaBundle\Entity\gestionBD\AtributoMedibleManual;
use GestionFaenaBundle\Form\gestionBD\AtributoMedibleAutomaticoType;
use GestionFaenaBundle\Entity\gestionBD\AtributoMedibleAutomatico;
use GestionFaenaBundle\Form\gestionBD\AtributoInformableExternoType;
use GestionFaenaBundle\Form\gestionBD\AtributoInformableArbitrarioType;
use GestionFaenaBundle\Entity\gestionBD\AtributoInformableExterno;
use GestionFaenaBundle\Entity\gestionBD\AtributoInformableArbitrario;
use GestionFaenaBundle\Form\ProcesoInicioType;
use GestionFaenaBundle\Form\ProcesoMedioType;
use GestionFaenaBundle\Form\ProcesoFinType;
use GestionFaenaBundle\Entity\ProcesoInicio;
use GestionFaenaBundle\Entity\ProcesoMedio;
use GestionFaenaBundle\Entity\ProcesoFin;
use GestionFaenaBundle\Entity\ProcesoFaena;
use GestionFaenaBundle\Entity\faena\AtributoConcepto;
use GestionFaenaBundle\Form\faena\AtributoConceptoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use GestionFaenaBundle\Repository\ProcesoFaenaRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints as Assert;
use GestionFaenaBundle\Form\gestionBD\AtrProcType;
use GestionFaenaBundle\Form\gestionBD\EntidadExternaType;
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
        return $this->render('@GestionFaena/gestionBD/articuloABM.html.twig', array('form' => $form->createView()));
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
     * @Route("/addEntExt", name="bd_add_entity_externa")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addEntityEternaAction()
    {
        $form = $this->createForm(EntidadExternaType::class, 
                                         null, 
                                         ['method' => 'POST']);
        return $this->render('@GestionFaena/gestionBD/abmEntidadExterna.html.twig', array('form' => $form->createView()));
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
     * @Route("/addAtr", name="bd_add_atributo")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAtributoMedibleManualAction()
    {
        $formAMM = $this->getFormABMAtributoMedibleManual(new AtributoMedibleManual());
        $formAMA = $this->getFormABMAtributoMedibleAutomatico(new AtributoMedibleAutomatico());
        $formAIE = $this->getFormABMAtributoInformableExterno(new AtributoInformableExterno());
        $formAIM = $this->getFormABMAtributoInformableManual(new AtributoInformableArbitrario());
        return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
    }

    private function getFormABMAtributoMedibleManual($atributo)
    {
        return $this->createForm(AtributoMedibleManualType::class, $atributo, ['action' => $this->generateUrl('bd_add_tributo_medible_manual_procesar'),'method' => 'POST']);
    }

    private function getFormABMAtributoMedibleAutomatico($atributo)
    {
        return $this->createForm(AtributoMedibleAutomaticoType::class, $atributo, ['action' => $this->generateUrl('bd_add_tributo_medible_automatico_procesar'),'method' => 'POST']);
    }

    private function getFormABMAtributoInformableExterno($atributo)
    {
        return $this->createForm(AtributoInformableExternoType::class, $atributo, ['action' => $this->generateUrl('bd_add_tributo_informable_procesar'),'method' => 'POST']);
    }

    private function getFormABMAtributoInformableManual($atributo)
    {
        return $this->createForm(AtributoInformableArbitrarioType::class, $atributo, ['action' => $this->generateUrl('bd_add_tributo_informable_manual_procesar'),'method' => 'POST']);
    }

    /**
     * @Route("/addAIMProc", name="bd_add_tributo_informable_manual_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoInformableManual(Request $request)
    {
        $atributo = new AtributoInformableArbitrario();
        $formAIM = $this->getFormABMAtributoInformableManual($atributo);
        $formAIM->handleRequest($request);
        if ($formAIM->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($atributo);
            $entityManager->flush();
            return $this->redirectToRoute('bd_add_atributo');
        }
        $formAMM = $this->getFormABMAtributoMedibleManual(new AtributoMedibleManual());
        $formAMA = $this->getFormABMAtributoMedibleAutomatico(new AtributoMedibleAutomatico());
        $formAIE = $this->getFormABMAtributoInformableExterno(new AtributoInformableExterno());
        
        return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('formAIM' => $formAIM->createView(),'formAI' => $formAIE->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
    }


    /**
     * @Route("/addAMMProc", name="bd_add_tributo_medible_manual_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoMedibleManual(Request $request)
    {
        $atributo = new AtributoMedibleManual();
        $form = $this->getFormABMAtributoMedibleManual($atributo);
        $formAMA = $this->getFormABMAtributoMedibleAutomatico(new AtributoMedibleAutomatico());
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($atributo);
            $entityManager->flush();
            return $this->redirectToRoute('bd_add_atributo');
        }

        return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('form' => $form->createView(), 'formAMA' => $formAMA->createView()));
    }

    /**
     * @Route("/addAMAProc", name="bd_add_tributo_medible_automatico_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoMedibleAutomatico(Request $request)
    {
        $form = $this->getFormABMAtributoMedibleManual(new AtributoMedibleManual());
        $atributo = new AtributoMedibleAutomatico();
        $formAMA = $this->getFormABMAtributoMedibleAutomatico($atributo);
        $formAMA->handleRequest($request);
        if ($formAMA->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($atributo);
            $entityManager->flush();
            return $this->redirectToRoute('bd_add_atributo');
        }
        return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('form' => $form->createView(), 'formAMA' => $formAMA->createView()));
    }

    /**
     * @Route("/addAIProc", name="bd_add_tributo_informable_procesar", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function procesarFormularioAtributoInformable(Request $request)
    {
        $formAMM = $this->getFormABMAtributoMedibleManual(new AtributoMedibleManual());
        $formAMA = $this->getFormABMAtributoMedibleAutomatico(new AtributoMedibleAutomatico());
        $atributo = new AtributoInformableExterno();
        $form = $this->getFormABMAtributoInformableExterno($atributo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($atributo);
            $entityManager->flush();
            return $this->redirectToRoute('bd_add_atributo');
        }
        return $this->render('@GestionFaena/gestionBD/atributoABM.html.twig', array('formAI' => $form->createView(),'form' => $formAMM->createView(), 'formAMA' => $formAMA->createView()));
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
        return $this->render('@GestionFaena/procesoEdit.html.twig', array('proccess' => $proceso, 'form' => $this->getFormAddDestinoProceso($proccess)->createView()));
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
    }

    /**
     * @Route("/editAtrArtProc", name="bd_editar_atributo_articulo", methods={"POST", "GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editarAtributoProceso(Request $request){
        $form = $this->getFormSelectAtrArtProc();
        return $this->render('@GestionFaena/gestionBD/articuloProcFanEdit.html.twig', array('form' => $form->createView()));
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
