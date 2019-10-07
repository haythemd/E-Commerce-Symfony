<?php

namespace GestionBoutiquesBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ProduitBoutiqueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomProduit')
            ->add('prix')
            ->add('description')
            ->add('image',FileType::class,array('label' => 'Image(JPG)'))
            //->add('boutique')
            ->add('categorie', EntityType::class, array(
                'class' => 'GestionBoutiquesBundle\Entity\Categorie',
                'choice_label' => 'nom',
                'multiple' => false,
            ))
            ->add('ajouter', SubmitType::class);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionBoutiquesBundle\Entity\ProduitBoutique'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionboutiquesbundle_produitboutique';
    }


}
