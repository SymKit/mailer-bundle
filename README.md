# Symkit Mailer Bundle

[![CI](https://github.com/symkit/mailer-bundle/actions/workflows/ci.yml/badge.svg)](https://github.com/symkit/mailer-bundle/actions)
[![Latest Version](https://img.shields.io/packagist/v/symkit/mailer-bundle.svg)](https://packagist.org/packages/symkit/mailer-bundle)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg)](https://phpstan.org/)

A Symfony bundle to manage and send emails from MJML-based templates, with event-driven logging and optional asynchronous sending via Symfony Messenger.

## Requirements

- PHP 8.2+
- Symfony 7.0+ or 8.0+
- [notfloran/mjml-bundle](https://github.com/NotFloran/mjml-bundle) (MJML rendering)
- [symkit/crud-bundle](https://github.com/symkit/crud-bundle) (admin UI)
- [symkit/form-bundle](https://github.com/symkit/form-bundle)

For asynchronous sending, install [symfony/messenger](https://symfony.com/doc/current/messenger.html) (see `composer suggest`).

## Installation

```bash
composer require symkit/mailer-bundle
```

Register the bundle in `config/bundles.php`:

```php
return [
    // ...
    Symkit\MailerBundle\SymkitMailerBundle::class => ['all' => true],
];
```

## Configuration

Create or edit `config/packages/symkit_mailer.yaml`:

```yaml
symkit_mailer:
    doctrine: true       # Entities and repositories (disable if not using Doctrine)
    logging: true       # Log sends to EmailLog (requires doctrine)
    messenger: true     # Register Messenger handler for async sending (requires symfony/messenger)
    admin:
        enabled: true
        route_prefix: symkit_mailer_admin
    entity:
        email_class: Symkit\MailerBundle\Entity\Email
        email_repository_class: Symkit\MailerBundle\Repository\EmailRepository
        layout_class: Symkit\MailerBundle\Entity\Layout
        layout_repository_class: Symkit\MailerBundle\Repository\LayoutRepository
        email_log_class: Symkit\MailerBundle\Entity\EmailLog
        email_log_repository_class: Symkit\MailerBundle\Repository\EmailLogRepository
```

If you override entity or repository classes (e.g. with subclasses), ensure your Doctrine mapping (e.g. `targetEntity` on relations) stays consistent with those classes.

### Admin routes

Mount the bundle routes with a prefix (e.g. under `/admin/email`):

```yaml
# config/routes.yaml
symkit_mailer_admin:
    resource: '@SymkitMailerBundle/config/routes.yaml'
    prefix: /admin/email
```

### MJML

Configure [notfloran/mjml-bundle](https://github.com/NotFloran/mjml-bundle) (e.g. `config/packages/mjml.yaml`) and set `MAILER_DSN` in your `.env` file.

## Usage

### Synchronous sending

Inject `Symkit\MailerBundle\Contract\EmailSenderInterface` and call `send()` with the template slug, recipient, and Twig context:

```php
$this->emailSender->send(
    'welcome',           // template slug (stored in DB)
    'user@example.com',  // recipient
    ['name' => 'John']   // Twig context
);
```

### Asynchronous sending (Messenger)

When `symkit_mailer.messenger` is enabled and `symfony/messenger` is installed, dispatch a message:

```php
$bus->dispatch(new SendEmailMessage('welcome', 'user@example.com', ['name' => 'John']));
```

## Events

The bundle dispatches the following events (subscribe to them for custom logging or side effects):

| Event               | When           | Payload (typical)                    |
|---------------------|----------------|--------------------------------------|
| `EmailSendingEvent` | Send started   | messageId, recipient, subject        |
| `EmailSentEvent`    | Send succeeded | messageId, HTML content              |
| `EmailFailedEvent`  | Send failed    | messageId, error message            |

When `logging` is enabled, `EmailLogSubscriber` persists these to the `EmailLog` entity.

## Development and quality

From the bundle root:

```bash
make cs-fix     # Fix code style
make phpstan    # Static analysis (level 9)
make test       # PHPUnit
make deptrac    # Architecture layers
make quality    # Full pipeline (cs-check, phpstan, deptrac, test, infection)
make ci         # Security check + quality
```

## License

MIT.
