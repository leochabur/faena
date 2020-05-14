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
use GestionFaenaBundle\Entity\opciones\InformeProceso;
use GestionFaenaBundle\Entity\FaenaDiaria;
use GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria;
use GestionFaenaBundle\Entity\faena\MovimientoStock;
use GestionFaenaBundle\Entity\opciones\AtributoInforme;
use GestionFaenaBundle\Form\opciones\AtributoInformeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InformesController extends Controller
{

    /**
     * @Route("/informes/existencias", name="informes_ver_existencias")

     */
    public function viewExistenciasAction(Request $request)
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')
        $form = $this->getFormSelectProceso();
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isValid())
            {
              $data = $form->getData();
              $em = $this->getDoctrine()->getManager();
              $repository = $em->getRepository('GestionFaenaBundle\Entity\faena\MovimientoStock');
              $stock = $repository->getStockArticulosPorProceso($data['proceso']);
              return $this->render('@GestionFaena/informes/existencias.html.twig', ['form' => $form->createView(), 'stock' => $stock]);
            }

        }
        return $this->render('@GestionFaena/informes/existencias.html.twig', ['form' => $form->createView()]);
    }

    private function getFormSelectProceso()
    {
        $form = $this->createFormBuilder()
                      ->add('proceso', 
                            EntityType::class, 
                            ['class' => ProcesoFaena::class, 
                             'required' => true,
                             'constraints' => [new NotNull(array('message' => "Debe seleccionar un proceso!!"))],
                             'query_builder' => function (EntityRepository $er) {
                                                                                        return $er->createQueryBuilder('p')
                                                                                                  ->where('p.permanente = :permanente')
                                                                                                  ->setParameter('permanente', true)
                                                                                                  ->orderBy('p.nombre', 'ASC');
                                                                                                }
                            ])
                        ->add('cargar', SubmitType::class, ['label' => 'Cargar'])
                        ->getForm();
        return $form;
    }

    /**
     * @Route("/informes/listi", name="informe_list_informes")
     */
    public function informesList()
    {
        $em = $this->getDoctrine()->getManager();
        $informes = $em->getRepository(InformeProceso::class)->findAll();        
        return $this->render('@GestionFaena/options/informesList.html.twig', ['informes'=> $informes]);
    }

    /**
     * @Route("/informes/addatri1/{i1}", name="informe_add_atributo_informe")
     */
    public function informeAddAtributoInforme($i1)
    {
        $em = $this->getDoctrine()->getManager();
        $informe = $em->find(InformeProceso::class, $i1);

        $atributo = new AtributoInforme();
        $atributo->setInforme($informe);
        $form = $this->createForm(AtributoInformeType::class, $atributo, ['action' => $this->generateUrl('informe_add_atributo_informe_procesar', ['i1' => $i1]), 'method' => 'POST']);
        return $this->render('@GestionFaena/options/addAtrInf.html.twig', ['informe' => $informe, 'form'=> $form->createView()]);
    }


    /**
     * @Route("/informes/addatri1proc/{i1}", name="informe_add_atributo_informe_procesar")
     */
    public function informeAddAtributoInformeProcesar($i1, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $informe = $em->find(InformeProceso::class, $i1);
        $atributo = new AtributoInforme();
        $atributo->setInforme($informe);
        $form = $this->createForm(AtributoInformeType::class, $atributo, ['method' => 'POST']);
        $form->handleRequest($request);
        if ($form->isValid())
        {
          $em->persist($atributo);
          $em->flush();
          return $this->redirectToRoute('informe_add_atributo_informe', ['i1' => $i1]);
        }
        return $this->render('@GestionFaena/options/addAtrInf.html.twig', ['informe' => $informe, 'form'=> $form->createView()]);
    }


    /**
     * @Route("/informes/informei/{fd}/{pfd}", name="informe_ingresos_egresos")

     */
    public function informeMovimientosAction($fd, $pfd)
    {
        $em = $this->getDoctrine()->getManager();
        $faenaDiaria = $em->find(FaenaDiaria::class, $fd);
        $proceso = $em->find(ProcesoFaenaDiaria::class, $pfd);
        $informe = $em->find(InformeProceso::class, 1);
        $repository = $em->getRepository(MovimientoStock::class);
        $movimientos = $repository->getAllEntradasStockProceso($proceso, $faenaDiaria, $informe);
        
        $detalle = array();

        foreach ($movimientos as $mov) 
        {
            $linea = array();
            foreach ($informe->getAtributos() as $atr) 
            {
                $data = $mov->getValorWhitAtribute($atr->getAtributo()->getId());
                $linea[$atr->getAtributo()->getId()] = ($data?$data->getData():'');
            }
            $detalle[] = $linea;
        }

        $columns_1 = array_column($detalle, $informe->getAtrWhitSort(1)->getAtributo()->getId());
        $columns_2 = array_column($detalle, $informe->getAtrWhitSort(2)->getAtributo()->getId());
        array_multisort($columns_1, SORT_DESC, $columns_2, SORT_ASC ,$detalle);

        $result = array(); //una vez que esta ordenado debe recorrer para realizar las sumas correspondientes, lo que seria la tabla efinitiva

        $totales = array(); //array para acumular los totoales

        $last = null;
        $agrupa = $informe->getAtributoAgrupa(); //atributo el cual sumariza los datos x Ej: Cargador

        $atributosAcumulables = $informe->getAtributosAcumulables(); //Devuelve lista de atributos que se acumulan ('s' -> sumable 'p' -> promediable)
            
        $totalGeneral = array('total' => true, $informe->getAtributos()->first()->getAtributo()->getId() => 'Totales:');
        $cant = 0; //para indicar la cantidad por la cual debe dividir el totoal para realizar el promedio
        foreach ($detalle as $det) 
        {          
          if (!$last)
          {
            $totales = array();
            $totales[$det[$agrupa->getAtributo()->getId()]] = $this->getInitializeArray($atributosAcumulables);
          }
          else
          {
              if ($last != $det[$agrupa->getAtributo()->getId()])
              {                
                $resTotales = array('total' => true);
                foreach ($totales as $line) 
                {
                  foreach ($line as $key => $value) 
                  {
                    if ($value['action'] == 's')
                      $resTotales[$key] = $value['sum'];
                    else
                      $resTotales[$key] = number_format(($value['sum']/$value['cant']),3);
                    if (array_key_exists($key, $totalGeneral))
                        $totalGeneral[$key]+= $resTotales[$key];
                    else
                        $totalGeneral[$key] = $resTotales[$key];
                  }
                }
                $cant++;
                $result[] = $resTotales;
                $last = null;
                $totales = array();
                $totales[$det[$agrupa->getAtributo()->getId()]] = $this->getInitializeArray($atributosAcumulables);
              }
          }

          foreach ($atributosAcumulables as $key => $value) 
          {
              $totales[$det[$agrupa->getAtributo()->getId()]][$key]['sum']+= $det[$key];
              $totales[$det[$agrupa->getAtributo()->getId()]][$key]['cant']++;
              $totales[$det[$agrupa->getAtributo()->getId()]][$key]['value'] = $value;
          }

          $last = $det[$agrupa->getAtributo()->getId()];

          $result[] = $det;
        }

        $resTotales = array('total' => true);
        $cant++;
        foreach ($totales as $line) 
        {
          foreach ($line as $key => $value) 
          {
                    if ($value['action'] == 's')
                      $resTotales[$key] = $value['sum'];
                    else
                      $resTotales[$key] = number_format(($value['sum']/$value['cant']), 3);

                    if (array_key_exists($key, $totalGeneral))
                        $totalGeneral[$key]+= $resTotales[$key];
                    else
                        $totalGeneral[$key] = $resTotales[$key];

                    if ($value['action'] == 'p')
                      $totalGeneral[$key] = number_format(($totalGeneral[$key]/$cant),3);
          }
        }
        $result[] = $resTotales;

        $result[] = $totalGeneral;
        return $this->render('@GestionFaena/informes/informeUno.html.twig', ['header' => $informe->getAtributos(), 'faena'=> $faenaDiaria, 'proceso' => $proceso, 'movimientos' => $result]);
    }


    private function getDetalleMovimientos($informe, $movimientos, $espejo, $factor, $aplicar)
    {
        $em = $this->getDoctrine()->getManager();
        $flush = false;
        $detalle = array();
        foreach ($movimientos as $mov) 
        {
            if ($factor)
            {
                $mov->setValorConAtributo($informe->getAtributoAjuste()->getAtributoAbstracto(), $factor);
                $mov->updateValues(0, $em);
            }

            $linea = array();
            foreach ($informe->getAtributos() as $atr) 
            {
                $data = $mov->getValorWhitAtribute($atr->getAtributo()->getId(), $espejo);
                $linea[$atr->getAtributo()->getId()] = ($data?$data->getData():'');
            }
            $detalle[] = $linea;
        }
        $columns_1 = array_column($detalle, $informe->getAtrWhitSort(1)->getAtributo()->getId());
        $columns_2 = array_column($detalle, $informe->getAtrWhitSort(2)->getAtributo()->getId());
        array_multisort($columns_1, SORT_DESC, $columns_2, SORT_ASC ,$detalle);
        $result = array(); //una vez que esta ordenado debe recorrer para realizar las sumas correspondientes, lo que seria la tabla efinitiva
        $totales = array(); //array para acumular los totoales
        $last = null;
        $agrupa = $informe->getAtributoAgrupa(); //atributo el cual sumariza los datos x Ej: Cargador
        $atributosAcumulables = $informe->getAtributosAcumulables(); //Devuelve lista de atributos que se acumulan ('s' -> sumable 'p' -> promediable)
        $totalGeneral = array('total' => true, $informe->getAtributos()->first()->getAtributo()->getId() => 'Totales:');
        $cant = 0; //para indicar la cantidad por la cual debe dividir el totoal para realizar el promedio
        foreach ($detalle as $det) 
        {          
          if (!$last)
          {
            $totales = array();
            $totales[$det[$agrupa->getAtributo()->getId()]] = $this->getInitializeArray($atributosAcumulables);
          }
          else
          {
              if ($last != $det[$agrupa->getAtributo()->getId()])
              {                
                $resTotales = array('total' => true);
                foreach ($totales as $line) 
                {
                  foreach ($line as $key => $value) 
                  {
                    if ($value['action'] == 's')
                      $resTotales[$key] = $value['sum'];
                    else
                      $resTotales[$key] = number_format(($value['sum']/$value['cant']),3);
                    if (array_key_exists($key, $totalGeneral))
                        $totalGeneral[$key]+= $resTotales[$key];
                    else
                        $totalGeneral[$key] = $resTotales[$key];
                  }
                }
                $cant++;
                $result[] = $resTotales;
                $last = null;
                $totales = array();
                $totales[$det[$agrupa->getAtributo()->getId()]] = $this->getInitializeArray($atributosAcumulables);
              }
          }
          foreach ($atributosAcumulables as $key => $value)
          {
              $totales[$det[$agrupa->getAtributo()->getId()]][$key]['sum']+= $det[$key];
              $totales[$det[$agrupa->getAtributo()->getId()]][$key]['cant']++;
              $totales[$det[$agrupa->getAtributo()->getId()]][$key]['value'] = $value;
          }
          $last = $det[$agrupa->getAtributo()->getId()];
          $result[] = $det;
        }
        $resTotales = array('total' => true);
        $cant++;
        foreach ($totales as $line) 
        {
          foreach ($line as $key => $value) 
          {
                    if ($value['action'] == 's')
                      $resTotales[$key] = $value['sum'];
                    else
                      $resTotales[$key] = number_format(($value['sum']/$value['cant']), 3);

                    if (array_key_exists($key, $totalGeneral))
                        $totalGeneral[$key]+= $resTotales[$key];
                    else
                        $totalGeneral[$key] = $resTotales[$key];

                    if ($value['action'] == 'p')
                      $totalGeneral[$key] = number_format(($totalGeneral[$key]/$cant),3);
          }
        }
        $result[] = $resTotales;
        $result[] = $totalGeneral;
        if ($aplicar)
          $em->flush();
        return $result;
    }

    private function getInitializeArray($atributosAcumulables)
    {
      $valores = array();
      foreach ($atributosAcumulables as $key => $value) {
          $valores[$key] = array('sum' => 0, 'cant' => 0, 'action' => $value);
      }
      return $valores;
    }

    /**
     * @Route("/informes/gerencia/informeii/{fd}/{proc}", name="informe_informe_dos")

     */
    public function informeDosAction($fd, $proc)
    {
        $em = $this->getDoctrine()->getManager();
        $faena = $em->find(FaenaDiaria::class, $fd);
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $informe = $em->find(InformeProceso::class, 1);
        $repository = $em->getRepository(MovimientoStock::class);
        $movimientos = $repository->getAllEntradasStockProceso($proceso, $faena, $informe);
        $result = $this->getDetalleMovimientos($informe, $movimientos, true, null, false);
        return $this->render('@GestionFaena/informes/informeDos.html.twig', ['header' => $informe->getAtributos(), 'faena'=> $faena, 'proceso' => $proceso, 'movimientos' => $result]);
    }

    /**
     * @Route("/informes/gerencia/informeiii/{fd}/{proc}", name="informe_informe_tres", methods={"POST", "GET"})

     */
    public function informeTresAction($fd, $proc, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $faena = $em->find(FaenaDiaria::class, $fd);
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);

        $form =    $this->createFormBuilder()
                        ->add('factor', 
                              TextType::class)
                        ->add('load', SubmitType::class, ['label' => 'Cargar Informe'])      
                        ->add('aplicar', SubmitType::class, ['label' => 'Aplicar Cambios'])    
                        ->getForm();

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            $aplicar = false;
            if ($form->get('aplicar')->isClicked())
            {
                $aplicar = true;
            }
            
            $data = $form->getData();
            $informe = $em->find(InformeProceso::class, 1);
            $repository = $em->getRepository(MovimientoStock::class);
            $movimientos = $repository->getAllEntradasStockProceso($proceso, $faena, $informe);
            $result = $this->getDetalleMovimientos($informe, $movimientos, true, $data['factor'], $aplicar);
            return $this->render('@GestionFaena/informes/informeTres.html.twig', ['header' => $informe->getAtributos(), 'faena'=> $faena, 'proceso' => $proceso, 'movimientos' => $result, 'form' => $form->createView()]);
        }
            return $this->render('@GestionFaena/informes/informeTres.html.twig', ['faena' => $faena, 'form' => $form->createView()]);
    }

    /**
     * @Route("/informes/expinone/{proc}/{fd}/{ajs}", name="export_informe_uno")
     */
    public function generatePdfInformeUno($proc, $fd, $ajs = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $faenaDiaria = $em->find(FaenaDiaria::class, $fd);
        $proceso = $em->find(ProcesoFaenaDiaria::class, $proc);
        $informe = $em->find(InformeProceso::class, 1);
        $repository = $em->getRepository(MovimientoStock::class);
        $movimientos = $repository->getAllEntradasStockProceso($proceso, $faenaDiaria, $informe);

        $proceso = $this->getDoctrine()->getManager()->find(ProcesoFaenaDiaria::class, $proc);
        $faena = $this->getDoctrine()->getManager()->find(FaenaDiaria::class, $fd);

       // $proceso = $faena->getProceso(1);
        $logo = $this->get('kernel')->getRootDir() . '/../web/resources/img/senasa.jpg';
        $pdf = $this->get('app.fpdf');
        $pdf->AliasNbPages();

        $detalle = $this->getDetalleMovimientos($informe, $movimientos, $ajs, null, false);
        
        $pdf->SetAutoPageBreak(false,0);  
        $pdf->AddPage('P', 'A4'); 
        $pdf = $this->getHeader($pdf, $faena);
        $pdf = $this->getBody($pdf, $detalle, $informe);
        return new Response($pdf->Output(), 200, array('Content-Type' => 'application/pdf'));  
    }


    private function getHeader($pdf, $faena)
    {
      $pdf->SetFont('Times','',12);
      $pdf->Cell( 0, 10, ''.$faena, 0, 1, 'C' ); 
      $pdf->SetFont('Times','',10);
      $pdf->SetFillColor(200);
      $pdf->Cell(30,10, "Granja",1,0,'C',1);
      $pdf->Cell(12,10, "Galpon",1,0,'C',1);
      $pdf->Cell(12,10, "O/C",1,0,'C',1);
      $pdf->Cell(20,10, "Trnsp.",1,0,'C',1);
      $pdf->Cell(20,10, "Aves",1,0,'C',1);
      $pdf->Cell(20,10, "DT-e",1,0,'C',1);
      $pdf->Cell(0,5, "Pesos",1,1,'C',1);


      $pdf->setX($pdf->getX()+114);
      $pdf->Cell(19,5, "Bruto",1,0,'C',1);
      $pdf->Cell(19,5, "Tara",1,0,'C',1);
      $pdf->Cell(19,5, "Neto",1,0,'C',1);
      $pdf->Cell(19,5, "Prom.",1,1,'C',1);
      return $pdf;
    }

    private function getBody($pdf, $detalle, $informe)
    {
      $columnDef = [
                      0 => ['s'=> 30, 'a' => 'C'],
                      1 => ['s'=> 12, 'a' => 'C'],
                      2 => ['s'=> 12, 'a' => 'C'],
                      3 => ['s'=> 20, 'a' => 'C'],
                      4 => ['s'=> 20, 'a' => 'R'],
                      5 => ['s'=> 20, 'a' => 'C'],
                      6 => ['s'=> 19, 'a' => 'R'],
                      7 => ['s'=> 19, 'a' => 'R'],
                      8 => ['s'=> 19, 'a' => 'R'],
                      9 => ['s'=> 19, 'a' => 'R'],
                      10 => ['s'=> 19, 'a' => 'R'],
                    ];
      $pdf->SetFont('Times','',8);
      $pdf->SetFillColor(255);
      foreach ($detalle as $det) 
      {
        $i = 0;
        foreach ($informe->getAtributos() as $atr)
        {
          $key = $atr->getAtributo()->getId();

          if (array_key_exists($key, $det))
          {   
              $data = $det[$key];
          }
          else
          {  
              $data = "";
          }
          if ($atr->getVisible())
            $pdf->Cell($columnDef[$i]['s'],6, $data,1,0,$columnDef[$i]['a'],1);
          $i++;
        }
        $pdf->ln();
      }
      return $pdf;
    }


}
