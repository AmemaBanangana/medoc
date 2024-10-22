<?php

namespace App\Form;

use App\Entity\Dosage;
use App\Entity\Medocs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DosageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dosage')
            // ->add('medocs', EntityType::class, [
            //     'class' => Medocs::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            //     'expanded' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dosage::class,
        ]);
    }
}
