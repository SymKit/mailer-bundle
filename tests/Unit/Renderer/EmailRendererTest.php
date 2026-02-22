<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Tests\Unit\Renderer;

use NotFloran\MjmlBundle\Renderer\RendererInterface;
use PHPUnit\Framework\TestCase;
use Symkit\MailerBundle\Entity\Email;
use Symkit\MailerBundle\Entity\Layout;
use Symkit\MailerBundle\Renderer\EmailRenderer;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class EmailRendererTest extends TestCase
{
    public function testRenderStandaloneContent(): void
    {
        $mjml = $this->createMock(RendererInterface::class);
        $mjml->expects(self::once())
            ->method('render')
            ->with(self::stringContains('<mjml>'))
            ->willReturn('<html><body>Rendered</body></html>');

        $twig = new Environment(new ArrayLoader());

        $email = new Email();
        $email->setContent('<mjml><mj-body>Hello</mj-body></mjml>');
        $email->setSubject('Test');

        $renderer = new EmailRenderer($mjml, $twig);
        $result = $renderer->render($email, []);

        self::assertSame('<html><body>Rendered</body></html>', $result);
    }

    public function testRenderStandaloneWhenLayoutIsNullAndContentWithoutMjml(): void
    {
        $content = '<p>Plain content</p>';
        $mjml = $this->createMock(RendererInterface::class);
        $mjml->expects(self::once())
            ->method('render')
            ->with(self::identicalTo($content))
            ->willReturn('<html><body>Rendered</body></html>');

        $twig = new Environment(new ArrayLoader());

        $email = new Email();
        $email->setContent($content);
        $email->setSubject('Test');
        $email->setLayout(null);

        $renderer = new EmailRenderer($mjml, $twig);
        $result = $renderer->render($email, []);

        self::assertSame('<html><body>Rendered</body></html>', $result);
    }

    public function testRenderWithLayoutUsesExtends(): void
    {
        $layoutContent = '<mjml><mj-head/><mj-body>{% block content %}{% endblock %}</mj-body></mjml>';
        $emailContent = '<mj-body>Email block</mj-body>';
        $mjml = $this->createMock(RendererInterface::class);
        $mjml->expects(self::once())
            ->method('render')
            ->with(self::callback(static function (string $mjmlSource) use ($emailContent): bool {
                return str_contains($mjmlSource, $emailContent);
            }))
            ->willReturn('<html><body>With layout</body></html>');

        $twig = new Environment(new ArrayLoader());
        $layout = new Layout();
        $layout->setContent($layoutContent);

        $email = new Email();
        $email->setContent($emailContent);
        $email->setSubject('Test');
        $email->setLayout($layout);

        $renderer = new EmailRenderer($mjml, $twig);
        $result = $renderer->render($email, []);

        self::assertSame('<html><body>With layout</body></html>', $result);
    }

    public function testRenderStandaloneWhenContentContainsMjmlEvenWithLayout(): void
    {
        $content = '<mjml><mj-body>Full document</mj-body></mjml>';
        $mjml = $this->createMock(RendererInterface::class);
        $mjml->expects(self::once())
            ->method('render')
            ->with(self::identicalTo($content))
            ->willReturn('<html><body>Rendered</body></html>');

        $twig = new Environment(new ArrayLoader());
        $layout = new Layout();
        $layout->setContent('<mjml><mj-head/></mjml>');

        $email = new Email();
        $email->setContent($content);
        $email->setSubject('Test');
        $email->setLayout($layout);

        $renderer = new EmailRenderer($mjml, $twig);
        $result = $renderer->render($email, []);

        self::assertSame('<html><body>Rendered</body></html>', $result);
    }

    public function testRenderDoesNotModifyOriginalTwigLoader(): void
    {
        $loader = new ArrayLoader();
        $twig = new Environment($loader);

        $mjml = $this->createMock(RendererInterface::class);
        $mjml->expects(self::once())
            ->method('render')
            ->willReturn('<html/>');

        $email = new Email();
        $email->setContent('<mjml></mjml>');
        $email->setSubject('Test');

        $renderer = new EmailRenderer($mjml, $twig);
        $renderer->render($email, []);

        self::assertSame($loader, $twig->getLoader());
    }
}
