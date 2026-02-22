<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\FormBundle\Form\Type\FormSectionType;

final class LayoutType extends AbstractType
{
    public function __construct(
        private readonly string $dataClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            $builder->create('general', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.section.general',
                'section_icon' => 'heroicons:information-circle-20-solid',
                'section_description' => 'form.section.general_layout_description',
            ])
                ->add('name', TextType::class, [
                    'label' => 'form.layout.name',
                    'attr' => ['placeholder' => 'form.layout.name_placeholder'],
                    'help' => 'form.layout.name_help',
                ]),
        );

        $builder->add(
            $builder->create('content_group', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.section.design_content',
                'section_icon' => 'heroicons:paint-brush-20-solid',
                'section_description' => 'form.section.design_description',
                'section_full_width' => true,
            ])
                ->add('content', TextareaType::class, [
                    'label' => 'form.layout.mjml_content',
                    'attr' => [
                        'placeholder' => '<mjml>...</mjml>',
                        'rows' => 20,
                        'class' => 'font-mono text-sm',
                    ],
                    'help' => 'form.layout.mjml_help',
                    'help_html' => true,
                ]),
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'translation_domain' => 'SymkitMailerBundle',
        ]);
    }
}
