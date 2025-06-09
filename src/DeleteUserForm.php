<?php

declare(strict_types=1);

namespace Iconic\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class DeleteUserForm extends AbstractType
{
    public function __construct(private RouterInterface $router)
    {


    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('delete', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-danger',
                    'onclick' => "return confirm('Delete?');",
                ],
                'label' => 'Delete',

            ])
            ->setAction($this->router->generate('delete-user'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
