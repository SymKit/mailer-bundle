<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Tests\Unit\Renderer;

use NotFloran\MjmlBundle\Renderer\RendererInterface;
use PHPUnit\Framework\TestCase;
use Symkit\MailerBundle\Entity\Email;
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
}
