<?php

namespace App\Form;

use App\Entity\WishMap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class WishMapType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
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
            ->add('category', TextType::class,
                ['label' => 'Category', 'required' => false,
                    'attr' => ['class' => 'card-title']])
            ->add('description', TextType::class,
                ['label' => 'Description',
                    'attr' => ['class' => 'card-text']])
            ->add('startDate', DateType::class,
                ['label' => 'Start date',
                    'attr' => ['class' => 'card-text']])
            ->add('finishDate', DateType::class,
                ['label' => 'Finish Date',
                    'attr' => ['class' => 'card-text']])
            ->add('process', NumberType::class,
                ['label' => 'Process of doing', 'required' => false,
                    'attr' => ['class' => 'card-text', 'min' => 0, 'max' => 100]])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WishMap::class,
        ]);
    }

}