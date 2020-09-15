<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('photo', FileType::class, [
            'label' => 'Avatar :',
            'attr' => ['class' => 'photo-form-edit'],
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Veuillez entrer un format de document valide',
                ])
            ],
        ])
        ->add('name', TextType::class, [
            'label' => 'Nom :',
            'attr' => ['class' => 'name-form']
        ])
        ->add('firstname', TextType::class, [
            'label' => 'Prénom :',
            'attr' => ['class' => 'firstname-form']
        ])
        ->add('Valider', SubmitType::class, ['attr' => ['class' => 'btn-submit-form-profil-edit']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}