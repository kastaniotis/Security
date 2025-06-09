<?php

declare(strict_types=1);

namespace Iconic\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGenerator;
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
                ],
                'label' => 'Delete',
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            $form->getConfig()->getOptions()['attr']['action'] = $this->router->generate('delete', ['id' => $data->getId()]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
