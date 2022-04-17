<?php

namespace App\Form;

use App\Entity\Punch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PunchType extends AbstractType
{


    /**
     * Permet de créer la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfiguration($label, $placeholder) {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
                ]  
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('theme', TextType::class, $this->getConfiguration("Thème", "Renseigne le thème narvalo"))
            // ->add('slug', HiddenType::class)
            ->add('content', TextareaType::class, $this->getConfiguration("Punchline", "Partage ton inspi ... fais deuspi"))
            ->add('date', DateType::class)
        ;

        // $this->createFormBuilder($punch)
        //             ->add('theme')
        //             ->add('slug')
        //             ->add('content')
        //             ->add('date')
        //             ->add('save', SubmitType::class, [
        //                 'label' => 'Valider',
        //                 'attr' => [
        //                     'class' => 'btn btn-primary'
        //                 ]
        //             ])
        //             ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Punch::class,
        ]);
    }
}
