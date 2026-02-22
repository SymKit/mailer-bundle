<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Controller\Admin;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symkit\CrudBundle\Controller\AbstractCrudController;
use Symkit\MailerBundle\Entity\EmailLog;

final class EmailLogCrudController extends AbstractCrudController
{
    private const TRANSLATION_DOMAIN = 'SymkitMailerBundle';

    public function __construct(
        private readonly string $entityClass = EmailLog::class,
        private readonly ?TranslatorInterface $translator = null,
    ) {
    }

    /** @param array<string, mixed> $parameters */
    private function trans(string $id, array $parameters = []): string
    {
        return $this->translator?->trans($id, $parameters, self::TRANSLATION_DOMAIN) ?? $id;
    }

    protected function getEntityClass(): string
    {
        return $this->entityClass;
    }

    protected function getFormClass(): string
    {
        return FormType::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'admin_emaillog';
    }

    /** @return array<string, array<string, mixed>> */
    protected function configureListFields(): array
    {
        return [
            'recipient' => [
                'label' => $this->trans('admin.emaillog.list.recipient'),
                'sortable' => true,
            ],
            'subject' => [
                'label' => $this->trans('admin.emaillog.list.subject'),
                'sortable' => true,
            ],
            'sentAt' => [
                'label' => $this->trans('admin.emaillog.list.sent_at'),
                'sortable' => true,
                'template' => '@SymkitCrud/crud/field/date.html.twig',
            ],
            'status' => [
                'label' => $this->trans('admin.emaillog.list.status'),
                'sortable' => true,
                'template' => '@SymkitCrud/crud/field/enum.html.twig',
                'map' => [
                    'sent' => 'success',
                    'failed' => 'danger',
                    'pending' => 'warning',
                ],
            ],
            'actions' => [
                'label' => '',
                'template' => '@SymkitCrud/crud/field/actions.html.twig',
                'show_route' => 'admin_emaillog_show',
                'header_class' => 'text-right',
                'cell_class' => 'text-right',
            ],
        ];
    }

    /** @return list<string> */
    protected function configureSearchFields(): array
    {
        return ['recipient', 'subject', 'status'];
    }

    /**
     * @return array<string, array{label: string, icon: string, description: string, full_width?: bool, fields: array<string, array<string, mixed>>}>
     */
    protected function configureShowSections(): array
    {
        return [
            'details' => [
                'label' => $this->trans('admin.emaillog.section.details'),
                'icon' => 'heroicons:information-circle-20-solid',
                'description' => $this->trans('admin.emaillog.section.details_description'),
                'fields' => array_merge($this->configureListFields(), [
                    'error' => [
                        'label' => $this->trans('admin.emaillog.section.error'),
                        'row_class' => 'sm:col-span-2',
                        'cell_class' => 'text-red-600 dark:text-red-400 font-medium',
                    ],
                ]),
            ],
            'content' => [
                'label' => $this->trans('admin.emaillog.section.content'),
                'icon' => 'heroicons:code-bracket-20-solid',
                'description' => $this->trans('admin.emaillog.section.content_description'),
                'full_width' => true,
                'fields' => [
                    'content' => [
                        'label' => $this->trans('admin.emaillog.section.source_code'),
                        'cell_class' => 'font-mono text-xs whitespace-pre-wrap p-4 bg-slate-50 dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-700 max-h-96 overflow-y-auto w-full block',
                    ],
                ],
            ],
            'preview' => [
                'label' => $this->trans('admin.emaillog.section.preview'),
                'icon' => 'heroicons:eye-20-solid',
                'description' => $this->trans('admin.emaillog.section.preview_description'),
                'full_width' => true,
                'fields' => [
                    'id' => [
                        'label' => false,
                        'template' => '@SymkitMailer/admin/email_log/field_preview.html.twig',
                    ],
                ],
            ],
        ];
    }

    public function list(Request $request): Response
    {
        return $this->renderIndex($request, [
            'page_title' => $this->trans('admin.emaillog.page_title'),
            'page_description' => $this->trans('admin.emaillog.page_description'),
            'create_route' => false,
        ]);
    }

    public function show(EmailLog $emailLog): Response
    {
        return $this->renderShow($emailLog, [
            'page_title' => $this->trans('admin.emaillog.show_title'),
            'page_description' => $this->trans('admin.emaillog.show_description'),
        ]);
    }
}
