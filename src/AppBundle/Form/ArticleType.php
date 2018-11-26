<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('articleType', ChoiceType::class, [
            'choices' => array(
              'Type d\'article' => '',
              'Annonce'         => 'Annonce',
              'Actualité'       => 'Actualité',
              'Témoignage'      => 'Témoignage'
            ), 'required' => TRUE, 'label' => 'Type de l\'article'
          ])
          ->add('title',         TextType::class,     ['required' => TRUE, 'label' => 'Titre'])
          ->add('description',   TextType::class,     ['required' => TRUE, 'label' => 'Description'])
          ->add('publited',      CheckboxType::class, ['required' => FALSE, 'label' => 'Publier l\'article', 'value' => "0"])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
      {
        $article = $event->getData();
        $form   = $event->getForm();

        if (!$article || null === $article->getId()) {
          $form
          ->add('img',  FileType::class,   ['required' => FALSE, 'label' => 'Image descriptive', 'data_class' => null])
          ->add('save', SubmitType::class, ['label' => 'Enregistrer l\'article']);
        }
        else{
          $form->add('update', SubmitType::class, array('label' => 'Modifier l\'article'));
        }
      });


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_article';
    }


}
