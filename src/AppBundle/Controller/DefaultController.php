<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GestionFaenaBundle\Form\FaenaDiariaType;
use GestionFaenaBundle\Entity\FaenaDiaria;
use Symfony\Component\HttpFoundation\Request;
use GestionFaenaBundle\Entity\ProcesoFaena;
use GestionFaenaBundle\Entity\faena\ProcesoFaenaDiaria;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use GestionFaenaBundle\Repository\FaenaDiariaRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use GestionFaenaBundle\Form\faena\EntradaStockType;
use GestionFaenaBundle\Form\faena\SalidaStockType;
use GestionFaenaBundle\Form\faena\TransformarStockType;
use GestionFaenaBundle\Form\faena\InitMoveStockType;
use GestionFaenaBundle\Entity\faena\EntradaStock;
use GestionFaenaBundle\Entity\faena\SalidaStock;
use GestionFaenaBundle\Entity\faena\TransformarStock;
use GestionFaenaBundle\Entity\faena\MovimientoStock;
use GestionFaenaBundle\Entity\faena\ConceptoMovimiento;
use GestionFaenaBundle\Entity\gestionBD\Granja;
use GestionFaenaBundle\Entity\gestionBD\Transportista;
use GestionFaenaBundle\Entity\gestionBD\ArticuloProcesoFaena;
use GestionFaenaBundle\Repository\gestionBD\ArticuloProcesoFaenaRepository; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class DefaultController extends Controller
{




}
