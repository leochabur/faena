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

class InformesController extends Controller
{

    /**
     * @Route("/informes/existencias", name="informes_ver_existencias")

     */
    public function viewExistenciasAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('@GestionFaena/Default/success.html.twig');
    }


}
