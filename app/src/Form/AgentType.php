<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('avatar')
            ->add('description')
            ->add('temperature')
            ->add('systemPrompt')
            ->add('model') // Ajout du champ model
            ->add('maxCompletionTokens') // Ajout du champ maxCompletionTokens
            ->add('utilisateurs', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'id',
                'multiple' => true,
                'by_reference' => false, // Assure que l'adder/remover est appelé
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
