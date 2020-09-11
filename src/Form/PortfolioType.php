<?php

namespace App\Form;

use App\Entity\Portfolio;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'attr' => ['class' => 'title-form']
            ])
            ->add('image', TextType::class, [
                'label' => 'Image :',
                'attr' => ['class' => 'image-form']
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégorie :',
                'attr' => ['class' => 'categories-form']
            ])
            ->add('background', FileType::class, [
                'label' => 'Fond :',
                'attr' => ['class' => 'background-form'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez entrer un format de document valide',
                    ])
                ],
            ])
            ->add('content', CKEditorType::class, ['label' => 'Contenu :'])
            ->add('Valider', SubmitType::class, ['attr' => ['class' => 'btn-submit-form']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
