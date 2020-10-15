<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom (obligatoire) :',
                'attr' => [
                    'class' => 'name-form',
                    'placeholder' => 'votre nom...'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom (obligatoire) :',
                'attr' => [
                    'class' => 'firstname-form',
                    'placeholder' => 'votre prénom...'
                    ]
            ])
            ->add('society', TextType::class, [
                'required' => false,
                'label' => 'Société :',
                'attr' => [
                    'class' => 'society-form',
                    'placeholder' => 'le nom de votre entreprise, si votre démarche est professionnelle...'
                    ]
            ])
            ->add('post', TextType::class, [
                'required' => false,
                'label' => 'Fonction :',
                'attr' => [
                    'class' => 'post-form',
                    'placeholder' => 'votre fonction, si votre démarche est professionnelle...'
                    ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Email (obligatoire) :',
                'attr' => [
                    'class' => 'email-form',
                    'placeholder' => 'votre Email...'
                    ]
            ])
            ->add('phone', TelType::class, [
                'required' => false,
                'label' => 'Téléphone :',
                'attr' => [
                    'class' => 'phone-form',
                    'placeholder' => 'votre numéro de téléphone...'
                    ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Sujet (obligatoire) :',
                'attr' => [
                    'class' => 'subject-form',
                    'placeholder' => 'le sujet de votre message...'
                    ]
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => 'Votre message (obligatoire) :',
                'attr' => [
                    'class' => 'textarea-form',
                    'placeholder' => 'votre message...'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J’accepte les conditions mentionnées ci-dessus et la politique de confidentialité : ',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
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
