<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('rating', IntegerType::class, $this->getConfiguration("Ta note sur 5", "Indique ta note sans trop licher", [
            'attr' => [
                'min' => 0,
                'max' => 5,
                'step' => 1
            ]
        ]))
        ->add('content', TextareaType::class, $this->getConfiguration("Ton commentaire", "La critique est facile, mais l'art est difficile"))
    ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
