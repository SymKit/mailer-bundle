<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Enum;

enum EmailStatus: string
{
    case PENDING = 'pending';

    case SENT = 'sent';

    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SENT => 'Sent',
            self::FAILED => 'Failed',
        };
    }
}
