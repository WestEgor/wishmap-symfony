<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,

                'first_options' => ['label' => 'Password',
                    'attr' => ['class' => 'form-control']],

                'second_options' => ['label' => 'Confirm password',
                    'attr' => ['class' => 'form-control']]
            ])
            ->add('nickname', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('avatar', FileType::class, [
                'label' => 'Image (JPEG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image (png/jpeg)',
                    ])
                ],
            ])
            ->add('profileDescription', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
