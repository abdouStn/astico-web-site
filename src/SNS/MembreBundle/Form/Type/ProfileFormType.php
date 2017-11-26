<?php


namespace SNS\MembreBundle\Form\Type;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use SNS\PlateformBundle\Form\PhotoType;

class ProfileFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);

        $builder->add('nom', TextType::class)
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
                ->add('photo', PhotoType::class, array(
                    //'data_class' => null,
                    'required' => false))
                ->add('current_password', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'), array(
                        'label' => 'form.current_password',
                        'translation_domain' => 'FOSUserBundle',
                        'mapped' => false,
                        'constraints' => new UserPassword(),
                        ))
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'profile',
            // BC for SF < 2.8
            'intention'  => 'profile',
        ));
    }

    // BC for SF < 2.7
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    // BC for SF < 3.0
    /*public function getName()
    {
        return $this->getBlockPrefix();
    }*/

    public function getName()
    {
        return 'SNS\MembreBundle\Form\Type\ProfileFormType::class';   // faudrait mieux utiliser le service sns_membre_registration mais ne marche pas
    }

    public function getBlockPrefix()
    {
        return 'fos_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ;
    }
}
