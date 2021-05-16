<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\WishMap;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
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
                'attr' => ['class' => 'form-label mt-4'],
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
            ->add('category', EntityType::class,
                ['class' => Category::class,
                    'choice_label' => 'name',
                    'multiple' => false,
                    'label' => 'Category', 'required' => false,
                    'attr' => ['class' => 'form-control form-control-sm text-primary description']])
            ->add('description', TextType::class,
                ['label' => 'Description',

                    'attr' => ['class' => 'form-control']])
            ->add('finishDate', DateType::class,
                ['label' => 'Finish Date', 'widget' => 'single_text', 'html5' => false,
                    'attr' => ['class' => 'js-datepicker']])
            ->add('process', RangeType::class,
                ['label' => 'Process of doing', 'required' => false,
                    'attr' => [
                        'class' => 'form-range',
                        'min' => 0, 'max' => 100,
                        'onchange' => 'updateTextInput(this.value);'
                    ]])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WishMap::class
        ]);
    }
    /*
     * */
}