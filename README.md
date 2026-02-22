# Symkit Mailer Bundle

[![CI](https://github.com/symkit/mailer-bundle/actions/workflows/ci.yml/badge.svg)](https://github.com/symkit/mailer-bundle/actions)
[![Latest Version](https://img.shields.io/packagist/v/symkit/mailer-bundle.svg)](https://packagist.org/packages/symkit/mailer-bundle)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg)](https://phpstan.org/)

Bundle Symfony pour gérer et envoyer des emails basés sur des modèles MJML, avec journalisation par événements et support asynchrone (Messenger).

## Prérequis

- PHP 8.2+
- Symfony 7.0+ ou 8.0+
- [notfloran/mjml-bundle](https://github.com/NotFloran/mjml-bundle) (rendu MJML)
- [symkit/crud-bundle](https://github.com/symkit/crud-bundle) (interface d’administration)
- [symkit/form-bundle](https://github.com/symkit/form-bundle)

## Installation

```bash
composer require symkit/mailer-bundle
```

Enregistrez le bundle dans `config/bundles.php` :

```php
return [
    // ...
    Symkit\MailerBundle\SymkitMailerBundle::class => ['all' => true],
];
```

## Configuration

Exemple dans `config/packages/symkit_mailer.yaml` :

```yaml
symkit_mailer:
    doctrine: true      # Entités et repositories (désactiver si vous n’utilisez pas Doctrine)
    logging: true       # Journalisation des envois (EmailLog)
    messenger: true      # Handler Messenger pour envoi asynchrone
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

### Routes de l’admin

Importez les routes du bundle avec un préfixe (par ex. `/admin/email`) :

```yaml
# config/routes.yaml
symkit_mailer_admin:
    resource: '@SymkitMailerBundle/config/routes.yaml'
    prefix: /admin/email
```

### MJML

Configurez [notfloran/mjml-bundle](https://github.com/NotFloran/mjml-bundle) (ex. `config/packages/mjml.yaml`) et définissez `MAILER_DSN` dans `.env`.

## Utilisation

### Envoi synchrone

Injectez `Symkit\MailerBundle\Contract\EmailSenderInterface` :

```php
$this->emailSender->send(
    'welcome',           // slug du modèle
    'user@example.com',  // destinataire
    ['name' => 'John']   // contexte Twig
);
```

### Envoi asynchrone (Messenger)

Déclenchez un envoi via la messagerie :

```php
$bus->dispatch(new SendEmailMessage('welcome', 'user@example.com', ['name' => 'John']));
```

## Événements

| Événement | Rôle |
|-----------|------|
| `EmailSendingEvent` | Envoi démarré (messageId, destinataire, sujet) |
| `EmailSentEvent` | Envoi réussi (messageId, contenu HTML) |
| `EmailFailedEvent` | Échec (messageId, message d’erreur) |

Le souscripteur `EmailLogSubscriber` enregistre ces événements en base (entité `EmailLog`) lorsque `logging` est activé.

## Qualité et tests

```bash
make cs-fix    # Style de code
make phpstan   # Analyse statique
make test      # PHPUnit
make deptrac   # Règles d’architecture
make quality   # Pipeline complète (cs-check, phpstan, deptrac, test, infection)
make ci        # security-check + quality
```

## Licence

MIT.
