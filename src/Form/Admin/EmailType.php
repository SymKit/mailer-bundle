<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Form\Admin;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symkit\FormBundle\Form\Type\FormSectionType;
use Symkit\FormBundle\Form\Type\SlugType;

final class EmailType extends AbstractType
{
    public function __construct(
        private readonly string $emailClass,
        private readonly string $layoutClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            $builder->create('general', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.section.general',
                'section_icon' => 'heroicons:information-circle-20-solid',
                'section_description' => 'form.email.general_description',
            ])
                ->add('subject', TextType::class, [
                    'label' => 'form.email.subject',
                    'attr' => ['placeholder' => 'form.email.subject_placeholder'],
                    'help' => 'form.email.subject_help',
                ])
                ->add('slug', SlugType::class, [
                    'label' => 'form.email.slug',
                    'target' => 'subject',
                    'unique' => true,
                    'entity_class' => $this->emailClass,
                    'help' => 'form.email.slug_help',
                ])
                ->add('layout', EntityType::class, [
                    'class' => $this->layoutClass,
                    'choice_label' => 'name',
                    'label' => 'form.email.layout',
                    'placeholder' => 'form.email.layout_placeholder',
                    'required' => false,
                    'help' => 'form.email.layout_help',
                ]),
        );

        $builder->add(
            $builder->create('sender', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.email.sender_section',
                'section_icon' => 'heroicons:paper-airplane-20-solid',
                'section_description' => 'form.email.sender_description',
            ])
                ->add('senderEmail', TextType::class, [
                    'label' => 'form.email.sender_email',
                    'attr' => ['placeholder' => 'form.email.sender_email_placeholder'],
                    'help' => 'form.email.sender_email_help',
                ])
                ->add('senderName', TextType::class, [
                    'label' => 'form.email.sender_name',
                    'attr' => ['placeholder' => 'form.email.sender_name_placeholder'],
                    'required' => false,
                    'help' => 'form.email.sender_name_help',
                ]),
        );

        $builder->add(
            $builder->create('content_group', FormSectionType::class, [
                'inherit_data' => true,
                'label' => 'form.email.content_section',
                'section_icon' => 'heroicons:document-text-20-solid',
                'section_description' => 'form.email.content_description',
                'section_full_width' => true,
            ])
                ->add('content', TextareaType::class, [
                    'label' => 'form.email.mjml_content',
                    'attr' => [
                        'placeholder' => '<mjml>...</mjml>',
                        'rows' => 15,
                        'class' => 'font-mono text-sm',
                    ],
                    'help' => 'form.email.mjml_help',
                    'help_html' => true,
                ]),
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->emailClass,
            'translation_domain' => 'SymkitMailerBundle',
        ]);
    }
}
