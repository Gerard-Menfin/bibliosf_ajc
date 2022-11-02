<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuteurModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "label"       => "Nom de l'auteur",
                "constraints" => [
                    new NotBlank([ "message" => "Le nom ne peut pas être vide" ]),
                    new Length([
                        "min"        => 4,
                        "minMessage" => "Le nom doit comporter au moins 4 caractères",
                        "max"        => 30,
                        "maxMessage" => "Le nom peut comporter 30 caractères maximum"
                    ])
                ],
            ])
            ->add('prenom', TextType::class, [
                "label"         => "Prénom",
                "constraints"   => [
                    new Length([
                        "min"        => 2,
                        "minMessage" => "Le nom doit comporter au moins 2 caractères",
                        "max"        => 30,
                        "maxMessage" => "Le nom peut comporter 30 caractères maximum"
                    ])
                ],
                "help"          => "Le prénom doit avoir entre 2 et 30 caractères",
                "required"      => false
            ])
            ->add('biographie')
            ->add("enregistrer", SubmitType::class)
            ->add("effacer", ResetType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
