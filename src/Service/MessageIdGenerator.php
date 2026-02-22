<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Service;

use Symfony\Component\Uid\Uuid;

final readonly class MessageIdGenerator
{
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
