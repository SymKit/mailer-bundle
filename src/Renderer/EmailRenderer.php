<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Renderer;

use NotFloran\MjmlBundle\Renderer\RendererInterface;
use Symkit\MailerBundle\Entity\Email;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;

final readonly class EmailRenderer
{
    public function __construct(
        private RendererInterface $mjml,
        private Environment $twig,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function render(Email $template, array $context = []): string
    {
        $layout = $template->getLayout();
        $content = $template->getContent() ?? '';
        $isStandalone = null === $layout || str_contains($content, '<mjml>');

        if ($isStandalone) {
            $arrayLoader = new ArrayLoader([
                'email.mjml.twig' => $content,
            ]);
            $templateName = 'email.mjml.twig';
        } else {
            $arrayLoader = new ArrayLoader([
                'layout.mjml.twig' => $layout->getContent(),
                'email.mjml.twig' => '{% extends "layout.mjml.twig" %} {% block content %}'.$content.'{% endblock %}',
            ]);
            $templateName = 'email.mjml.twig';
        }

        $env = clone $this->twig;
        $env->setLoader(new ChainLoader([
            $arrayLoader,
            $this->twig->getLoader(),
        ]));

        $mjmlContent = $env->render($templateName, $context);

        return $this->mjml->render($mjmlContent);
    }
}
