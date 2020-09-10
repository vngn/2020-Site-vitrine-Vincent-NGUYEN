<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom '])
            ->add('firstname', TextType::class, ['label' => 'Prénom '])
            ->add('society', TextType::class, ['label' => 'Société '])
            ->add('post', TextType::class, ['label' => 'Fonction '])
            ->add('email', TextType::class, ['label' => 'Email '])
            ->add('phone', TelType::class, ['label' => 'Téléphone '])
            ->add('title', TextType::class, ['label' => 'Sujet '])
            ->add('content', CKEditorType::class, ['label' => 'Votre message :'])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
