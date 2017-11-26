<?php

namespace SNS\PlateformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intutile', TextType::class)
            ->add('dateDebut', DateType::class)
            //->add('dateSoumission', 'date')
            ->add('duree', IntegerType::class)
            ->add('lieu', TextType::class)
            ->add('contacte', TextType::class)
            ->add('description', TextareaType::class)
            ->add('parcours', ChoiceType::class, array(
                'choices'=>array(
                    'BCD' => 'BCD',
                    'IDS' => 'IDS',
                    'PHYMED' => 'PHYMED',
                    'Autre' => 'Autre'),
                'placeholder'=>'Choisir parcours',
                'multiple'=>false))
            ->add('file', FileType::class,
                array( 'label' => 'Fichier',
                    'required' => false
                    ))
            //->add('auteur')
            ->add('save', SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SNS\PlateformBundle\Entity\Stage'
        ));
    }
}
