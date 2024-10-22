<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\CommandeMedicament;
use App\Entity\Medocs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CommandeMedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('medicament', EntityType::class, [
            'class' => Medocs::class,
            'choice_label' => 'nom',
            'label' => 'Médicament'
        ])
        ->add('quantite', IntegerType::class, ['label' => 'Quantité']);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommandeMedicament::class,
        ]);
    }
}
