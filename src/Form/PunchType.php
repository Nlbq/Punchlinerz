<?php

namespace App\Form;

use App\Entity\Punch;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PunchType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('theme', TextType::class, $this->getConfiguration("Thème", "Renseigne le thème narvalo"))
            ->add('content', TextareaType::class, $this->getConfiguration("Punchline", "Partage ton inspi ... fais deuspi"))
            ->add('date', DateType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Punch::class,
        ]);
    }
}
