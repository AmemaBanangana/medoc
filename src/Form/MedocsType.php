<?php

namespace App\Form;

use App\Entity\Forme;
use App\Entity\Dosage;
use App\Entity\Medocs;
use App\Entity\Ordonance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MedocsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('date_exp', null, [
                'widget' => 'single_text',
            ])
            ->add('forme', EntityType::class, [
                'class' => Forme::class,
                'choice_label' => 'forme',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('dosage', EntityType::class, [
                'class' => Dosage::class,
                'choice_label' => 'dosage',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('stock')
            ->add('photo', FileType::class, [
                'label' => 'Photo (JPEG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1270k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG or PNG)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medocs::class,
        ]);
    }
}
