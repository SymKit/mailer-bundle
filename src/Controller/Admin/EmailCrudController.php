<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symkit\CrudBundle\Controller\AbstractCrudController;
use Symkit\MailerBundle\Entity\Email;
use Symkit\MailerBundle\Form\Admin\EmailType;

final class EmailCrudController extends AbstractCrudController
{
    private const TRANSLATION_DOMAIN = 'SymkitMailerBundle';

    public function __construct(
        private readonly string $entityClass,
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
        return EmailType::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'admin_email';
    }

    /** @return array<string, array<string, mixed>> */
    protected function configureListFields(): array
    {
        return [
            'subject' => [
                'label' => $this->trans('admin.email.list.subject'),
                'sortable' => true,
            ],
            'slug' => [
                'label' => $this->trans('admin.email.list.slug'),
                'sortable' => true,
                'cell_class' => 'font-mono text-xs',
            ],
            'senderEmail' => [
                'label' => $this->trans('admin.email.list.sender'),
                'sortable' => true,
            ],
            'senderName' => [
                'label' => $this->trans('admin.email.list.name'),
                'sortable' => true,
            ],
            'layout' => [
                'label' => $this->trans('admin.email.list.layout'),
                'sortable' => true,
            ],
            'actions' => [
                'label' => '',
                'template' => '@SymkitCrud/crud/field/actions.html.twig',
                'edit_route' => 'admin_email_edit',
                'header_class' => 'text-right',
                'cell_class' => 'text-right',
            ],
        ];
    }

    /** @return list<string> */
    protected function configureSearchFields(): array
    {
        return ['subject', 'slug'];
    }

    public function list(Request $request): Response
    {
        return $this->renderIndex($request, [
            'page_title' => $this->trans('admin.email.page_title'),
            'page_description' => $this->trans('admin.email.page_description'),
        ]);
    }

    public function create(Request $request): Response
    {
        $entity = new ($this->entityClass)();

        return $this->renderNew($entity, $request, [
            'page_title' => $this->trans('admin.email.create_title'),
            'page_description' => $this->trans('admin.email.create_description'),
        ]);
    }

    public function edit(Email $email, Request $request): Response
    {
        return $this->renderEdit($email, $request, [
            'page_title' => $this->trans('admin.email.edit_title', ['%subject%' => $email->getSubject() ?? '']),
            'page_description' => $this->trans('admin.email.edit_description'),
        ]);
    }

    public function delete(Email $email, Request $request): Response
    {
        return $this->performDelete($email, $request);
    }
}
