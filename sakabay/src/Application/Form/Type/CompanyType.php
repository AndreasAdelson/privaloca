<?php

namespace App\Application\Form\Type;

use Symfony\Component\Validator\Constraints\NotBlank;
use App\Domain\Model\Company;
use PhpParser\Parser\Multiple;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $options['translator'];

        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => $translator->trans('error_message_field_not_empty'),
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('numSiret', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => $translator->trans('error_message_field_not_empty'),
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            // ->add('nomCostumer', TextType::class, [
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Merci d\'entrer un first name',
            //         ]),
            //     ],
            //     'required' => true,
            //     'attr' => ['class' => 'form-control'],
            // ])
            // ->add('lastNameCostumer', TextType::class, [
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Merci d\'entrer un last name',
            //         ]),
            //     ],
            //     'required' => true,
            //     'attr' => ['class' => 'form-control'],
            // ])
            // ->add('email', EmailType::class, [
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Merci d\'entrer un last name',
            //         ]),
            //     ],
            //     'required' => true,
            //     'attr' => ['class' => 'form-control'],
            // ])
            ->add('category', EntityType::class, [
                'class' => 'App:Category',
                'constraints' => [
                    new NotNull([
                        'message' => $translator->trans('error_message_field_not_empty'),
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'csrf_protection' => false,
        ]);
        $resolver->setRequired('translator');
    }
}
