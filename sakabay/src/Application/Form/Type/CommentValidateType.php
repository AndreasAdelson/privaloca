<?php

namespace App\Application\Form\Type;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use App\Domain\Model\Comment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class CommentValidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $options['translator'];

        $builder
            ->add('message', TextType::class, [
                'constraints' => [
                    new Length(['max' => 191]),
                ],
                'required' => false,
            ])
            ->add('note', NumberType::class, [
                'constraints' => [
                    new Length(['max' => 3]),
                ],
                'required' => false,
            ])
            ->add('company', EntityType::class, [
                'class' => 'App:Company',
                'required' => false,
            ])
            ->add('besoin', EntityType::class, [
                'class' => 'App:Besoin',
                'required' => false,
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => 'App:Utilisateur',
                'required' => true
            ])
            ->add('authorCompany', EntityType::class, [
                'class' => 'App:Company',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_protection' => false,
        ]);
        $resolver->setRequired('translator');
    }
}
