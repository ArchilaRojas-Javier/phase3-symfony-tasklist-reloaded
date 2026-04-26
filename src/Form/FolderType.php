<?php

namespace App\Form;

use App\Entity\Folder;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FolderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('color', ChoiceType::class, [
                'choices' => [
                    'Rouge' => '#FF0000',
                    'Vert' => '#00FF00',
                    'Bleu' => '#0000FF',
                    'Jaune' => '#FFD700',
                    'Violet' => '#9933CC',
                    'Gris' => '#999999',
                    'Noir' => '#000000',
                    'marron' => '#A52A2A',
                    'Orange' => '#FFA500',
                    'Rose' => '#FF69B4',
                    'Cyan' => '#00FFFF',
                    'Magenta' => '#FF00FF',
                    'Turquoise' => '#40E0D0',
                    'Indigo' => '#4B0082',
                    'Or' => '#FFD700',
                    'Argent' => '#C0C0C0',
                    'Bronze' => '#CD7F32',
                    'Corail' => '#FF7F50',
                ],
                'label' => 'Couleur',
                'expanded' => true,
                'multiple' => false,
                'label_attr' => ['class' => 'block mb-2 font-medium'],
                'choice_attr' => function($choice, $key, $value) {
            
                    return ['data-color' => $choice, $key, $value];
                },
            ]);
    }




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Folder::class,
        ]);
    }
}
