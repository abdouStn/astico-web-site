<?php

namespace SNS\MembreBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use SNS\PlateformBundle\Form\PhotoType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        // add your custom field
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('specialite', ChoiceType::class, array(
                'choices'=>array(
                    'BCD' => 'BCD',
                    'IDS' => 'IDS',
                    'PHYMED' => 'PHYMED',
                    'Autre' => 'Autre'),
                'placeholder'=>'Choisir parcours',
                'multiple'=>false))
            ->add('anniversaire', DateType::class)
            //->add('photo', PhotoType::class, array(
                  //  'required' => false))
        ;
        parent::buildForm($builder, $options);


    }

    public function getName()
    {
        return 'SNS\MembreBundle\Form\Type\RegistrationFormType::class';   // faudrait mieux utiliser le service sns_membre_registration mais ne marche pas
    }
}
