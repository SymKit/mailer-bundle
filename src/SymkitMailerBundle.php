<?php

declare(strict_types=1);

namespace Symkit\MailerBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symkit\MailerBundle\Controller\Admin\EmailCrudController;
use Symkit\MailerBundle\Controller\Admin\EmailLogCrudController;
use Symkit\MailerBundle\Controller\Admin\LayoutCrudController;
use Symkit\MailerBundle\Entity\Email;
use Symkit\MailerBundle\Entity\EmailLog;
use Symkit\MailerBundle\Entity\Layout;
use Symkit\MailerBundle\Form\Admin\EmailType;
use Symkit\MailerBundle\Form\Admin\LayoutType;
use Symkit\MailerBundle\Repository\EmailLogRepository;
use Symkit\MailerBundle\Repository\EmailRepository;
use Symkit\MailerBundle\Repository\LayoutRepository;

class SymkitMailerBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('email_class')->defaultValue(Email::class)->end()
                        ->scalarNode('email_repository_class')->defaultValue(EmailRepository::class)->end()
                        ->scalarNode('layout_class')->defaultValue(Layout::class)->end()
                        ->scalarNode('layout_repository_class')->defaultValue(LayoutRepository::class)->end()
                        ->scalarNode('email_log_class')->defaultValue(EmailLog::class)->end()
                        ->scalarNode('email_log_repository_class')->defaultValue(EmailLogRepository::class)->end()
                    ->end()
                ->end()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('route_prefix')->defaultValue('symkit_mailer_admin')->end()
                    ->end()
                ->end()
                ->booleanNode('doctrine')->defaultTrue()->end()
                ->booleanNode('messenger')->defaultTrue()->end()
                ->booleanNode('logging')->defaultTrue()->end()
            ->end();
    }

    /**
     * @param array{
     *     entity: array{
     *         email_class: string,
     *         email_repository_class: string,
     *         layout_class: string,
     *         layout_repository_class: string,
     *         email_log_class: string,
     *         email_log_repository_class: string,
     *     },
     *     admin: array{enabled: bool, route_prefix: string},
     *     doctrine: bool,
     *     messenger: bool,
     *     logging: bool,
     * } $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->parameters()
            ->set('symkit_mailer.entity.email_class', $config['entity']['email_class'])
            ->set('symkit_mailer.entity.email_repository_class', $config['entity']['email_repository_class'])
            ->set('symkit_mailer.entity.layout_class', $config['entity']['layout_class'])
            ->set('symkit_mailer.entity.layout_repository_class', $config['entity']['layout_repository_class'])
            ->set('symkit_mailer.entity.email_log_class', $config['entity']['email_log_class'])
            ->set('symkit_mailer.entity.email_log_repository_class', $config['entity']['email_log_repository_class'])
            ->set('symkit_mailer.admin.route_prefix', $config['admin']['route_prefix']);

        $services = $container->services()->defaults()->autowire()->autoconfigure()->private();

        if ($config['doctrine']) {
            $services->set(LayoutRepository::class)
                ->tag('doctrine.repository_service')
                ->arg('$entityClass', '%symkit_mailer.entity.layout_class%');
            $services->set(EmailRepository::class)
                ->tag('doctrine.repository_service')
                ->arg('$entityClass', '%symkit_mailer.entity.email_class%');
            $services->set(EmailLogRepository::class)
                ->tag('doctrine.repository_service')
                ->arg('$entityClass', '%symkit_mailer.entity.email_log_class%');
        }

        $services->set(Renderer\EmailRenderer::class);
        $services->set(Service\MessageIdGenerator::class);

        if ($config['doctrine']) {
            $services->set(Sender\EmailSender::class);
        }

        if ($config['logging']) {
            $services->set(Log\EmailLogger::class)
                ->arg('$emailLogClass', '%symkit_mailer.entity.email_log_class%');
            $services->set(EventSubscriber\EmailLogSubscriber::class)
                ->tag('kernel.event_subscriber');
        }

        if ($config['messenger']) {
            $services->set(MessageHandler\SendEmailMessageHandler::class)
                ->tag('messenger.message_handler');
        }

        if ($config['admin']['enabled']) {
            $services->set(LayoutType::class)
                ->arg('$dataClass', '%symkit_mailer.entity.layout_class%');
            $services->set(EmailType::class)
                ->arg('$emailClass', '%symkit_mailer.entity.email_class%')
                ->arg('$layoutClass', '%symkit_mailer.entity.layout_class%');
            $services->set(LayoutCrudController::class)
                ->tag('controller.service_arguments')
                ->arg('$entityClass', '%symkit_mailer.entity.layout_class%');
            $services->set(EmailCrudController::class)
                ->tag('controller.service_arguments')
                ->arg('$entityClass', '%symkit_mailer.entity.email_class%');
            $services->set(EmailLogCrudController::class)
                ->tag('controller.service_arguments')
                ->arg('$entityClass', '%symkit_mailer.entity.email_log_class%');
        }
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->extension('twig', [
            'paths' => [
                $this->getPath().'/templates' => 'SymkitMailer',
            ],
        ], true);
    }

    protected string $extensionAlias = 'symkit_mailer';
}
