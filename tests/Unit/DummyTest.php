<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class DummyTest extends TestCase
{
    public function testBundleNamespaceExists(): void
    {
        self::assertTrue(class_exists(\Symkit\MailerBundle\SymkitMailerBundle::class));
    }
}
