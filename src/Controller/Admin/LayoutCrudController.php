<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symkit\CrudBundle\Controller\AbstractCrudController;
use Symkit\MailerBundle\Entity\Layout;
use Symkit\MailerBundle\Form\Admin\LayoutType;

final class LayoutCrudController extends AbstractCrudController
{
    private const TRANSLATION_DOMAIN = 'SymkitMailerBundle';

    public function __construct(
        private readonly string $entityClass = Layout::class,
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
        return LayoutType::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'admin_layout';
    }

    /** @return array<string, array<string, mixed>> */
    protected function configureListFields(): array
    {
        return [
            'name' => [
                'label' => $this->trans('admin.layout.list.name'),
                'sortable' => true,
            ],
            'emailCount' => [
                'label' => $this->trans('admin.layout.list.emails'),
                'sortable' => false,
            ],
            'actions' => [
                'label' => '',
                'template' => '@SymkitCrud/crud/field/actions.html.twig',
                'edit_route' => 'admin_layout_edit',
                'header_class' => 'text-right',
                'cell_class' => 'text-right',
            ],
        ];
    }

    /** @return list<string> */
    protected function configureSearchFields(): array
    {
        return ['name'];
    }

    public function list(Request $request): Response
    {
        return $this->renderIndex($request, [
            'page_title' => $this->trans('admin.layout.page_title'),
            'page_description' => $this->trans('admin.layout.page_description'),
        ]);
    }

    public function create(Request $request): Response
    {
        $entity = new ($this->entityClass)();

        return $this->renderNew($entity, $request, [
            'page_title' => $this->trans('admin.layout.create_title'),
            'page_description' => $this->trans('admin.layout.create_description'),
        ]);
    }

    public function edit(Layout $layout, Request $request): Response
    {
        return $this->renderEdit($layout, $request, [
            'page_title' => 'Edit '.($layout->getName() ?? ''),
            'page_description' => $this->trans('admin.layout.edit_description'),
        ]);
    }

    public function delete(Layout $layout, Request $request): Response
    {
        return $this->performDelete($layout, $request);
    }
}
