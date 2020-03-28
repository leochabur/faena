<?php

namespace GestionFaenaBundle\Form\gestionBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
class AtributoMedibleAutomaticoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $articulo = $options['articulo'];
        $builder->add('factor1', 
                      FactorCalculoType::class,
                      ['required' => false,
                       'articulo' => $articulo]
                    )
                ->add('factor2', 
                      FactorCalculoType::class,
                      ['required' => false,
                    'articulo' => $articulo]
                     )
                ->add('operacion', ChoiceType::class, ['choices'  => ['Replicar' => 'R', '+' => '+', '-' => '-', '*' => '*', '/' => '/']])
                ->add('atributoMedible', AtributoMedibleType::class, array(
                                    'data_class' => 'GestionFaenaBundle\Entity\gestionBD\AtributoMedibleAutomatico',
                                ))
                ->add('factorAjuste')
                ->add('guardar', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                                    'data_class' => 'GestionFaenaBundle\Entity\gestionBD\AtributoMedibleAutomatico'
                              ))
                  ->setRequired('articulo'); //para cuando deba definir el calculo, solo muestre los atributos propios del articulo y no tdos
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionfaenabundle_gestionbd_atributomedibleautomatico';
    }
/*', 
                      EntityType::class, 
                      [ 'class' => 'GestionFaenaBundle\Entity\gestionBD\AtributoMedible', 
                        'multiple' => false,
                        'query_builder' => function (EntityRepository $er) {
                                                                                return $er->createQueryBuilder('u')
                                                                                          ->where('u.activo = :activo')
                                                                                          ->setParameter('activo', true)
                                                                                          ->orderBy('u.id', 'DESC');
                                                                            }

                      ]*/

}
