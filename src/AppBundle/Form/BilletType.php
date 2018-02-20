<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

class BilletType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'Nom',
                'attr' => array(
                    'placeholder' => 'Nom'
                ),
            ))
            ->add('prenom', TextType::class, array(
                'label' => 'Prénom',
                'attr' => array(
                    'placeholder' => 'Prénom'
                ),
            ))
            ->add('reduction', CheckboxType::class, array(
                'label' => 'Tarif réduit',
                'required' => false,
            ))
            ->add('dateNaissance', DateType::class, array(
                'label' => 'Date de naissance',
                'format' => 'dd / MM / yyyy',
                'placeholder' => array(
                    'year' => 'AAAA',
                    'month' => 'MM',
                    'day' => 'JJ'
                ),
                'years' => range(2018, 1918),
            ))
            ->add('pays', CountryType::class, array(
                'label' => 'Pays',
                'placeholder' => 'Sélectionnez un pays',
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Billet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_billet';
    }


}
