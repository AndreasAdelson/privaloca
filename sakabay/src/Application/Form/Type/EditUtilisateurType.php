<?php

namespace App\Application\Form\Type;

use Symfony\Component\Validator\Constraints\NotBlank;
use App\Domain\Model\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $options['translator'];

        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un e-mail',
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('login', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un login',
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un first name',
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un last name',
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Editeur' => 'ROLE_EDITOR',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'csrf_protection' => false,
        ]);
        $resolver->setRequired('translator');
    }
}
