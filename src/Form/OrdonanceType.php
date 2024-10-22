<?php

namespace App\Form;

use App\Entity\Medecin;
use App\Entity\Medocs;
use App\Entity\Ordonance;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Patien', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'prenom',
            ])
            ->add('medicament', EntityType::class, [
                'class' => Medocs::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('medecin', EntityType::class, [
                'class' => Medecin::class,
                'choice_label' => 'prenom',
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonance::class,
        ]);
    }
}
