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
            ->add('name', TextType::class, [
                'label' => 'Nom* :',
                'attr' => ['class' => 'name-form']
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom* :',
                'attr' => ['class' => 'firstname-form']
            ])
            ->add('society', TextType::class, [
                'required' => false,
                'label' => 'Société :',
                'attr' => ['class' => 'society-form']
            ])
            ->add('post', TextType::class, [
                'required' => false,
                'label' => 'Fonction :',
                'attr' => ['class' => 'post-form']
            ])
            ->add('email', TextType::class, [
                'label' => 'Email* :',
                'attr' => ['class' => 'email-form']
            ])
            ->add('phone', TelType::class, [
                'required' => false,
                'label' => 'Téléphone :',
                'attr' => ['class' => 'phone-form']
            ])
            ->add('title', TextType::class, [
                'label' => 'Sujet* :',
                'attr' => ['class' => 'subject-form']
            ])
            ->add('content', CKEditorType::class, [
                'required' => false,
                'label' => 'Votre message* :'
                ])
            ->add('Valider', SubmitType::class, ['attr' => ['class' => 'btn-submit-form']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
