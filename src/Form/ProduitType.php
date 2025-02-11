<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('prixVenteHt', NumberType::class, [
                'label' => 'Prix de vente HT',
                'scale' => 2,
                'attr' => ['class' => 'form-control']
            ])
            ->add('prescription', ChoiceType::class, [
                'label' => 'Prescription requise',
                'choices' => [
                    'Non' => 'non',
                    'Oui' => 'oui'
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('tauxRemboursement', IntegerType::class, [
                'label' => 'Taux de remboursement (%)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 100
                ]
            ])
            ->add('alerte', IntegerType::class, [
                'label' => 'Seuil d\'alerte stock',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('declencherAlerte', ChoiceType::class, [
                'label' => 'Activer l\'alerte',
                'choices' => [
                    'Non' => 'non',
                    'Oui' => 'oui'
                ],
                'attr' => ['class' => 'form-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
} 