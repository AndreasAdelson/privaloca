<?php

namespace App\Application\Form\Type;

use Symfony\Component\Validator\Constraints\NotBlank;
use App\Domain\Model\Subscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $options['translator'];

        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => $translator->trans('error_message_field_not_empty'),
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('code', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => $translator->trans('error_message_field_not_empty'),
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => $translator->trans('error_message_field_not_empty'),
                    ]),
                ],
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('advantages', EntityType::class, [
                'class' => 'App:Advantage',
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => $translator->trans('error_message_field_not_empty')
                    ])
                ],
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
            'csrf_protection' => false,
        ]);
        $resolver->setRequired('translator');
    }
}
