<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('roles', ChoiceType::class, [
                "choices"   => [
                    "Abonné"            => "ROLE_ABONNE",
                    "Bibliothécaire"    => "ROLE_BIBLIO",
                    "Directeur"         => "ROLE_ADMIN"
                ],
                "multiple"  => true,
                "expanded"  => true,
                "label"     => "Accréditation"
            ])
            ->add('password', PasswordType::class, [
                /* l'option 'mapped' avec la valeur false, permet de préciser que ce champ
                   du formulaire ne sera pas lié à une propriété de l'objet utilisé pour
                   créer le formulaire */
                "mapped"    => false,
                "required"  => false
            ])
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
