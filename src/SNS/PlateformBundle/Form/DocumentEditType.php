<?php
// On a ici le principe d'heritage de formulaire. herite de DocumentEditType via la methode getParent. a tester pour la modification d'annonce.

namespace SNS\PlateformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DocumentEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    //$builder->remove('date');
    		//->remove('auteur');
   
  }

  /*public function getName()
  {
    return 'sns_platform_edit_document';
  }*/

  public function getParent()
  {
    return DocumentType::class;
  }
}
