<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symkit\MailerBundle\Service\MessageIdGenerator;

final class MessageIdGeneratorTest extends TestCase
{
    public function testGenerateReturnsUuidV4Format(): void
    {
        $generator = new MessageIdGenerator();
        $id = $generator->generate();
        self::assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $id,
        );
    }

    public function testGenerateReturnsDifferentIdsEachTime(): void
    {
        $generator = new MessageIdGenerator();
        $id1 = $generator->generate();
        $id2 = $generator->generate();
        self::assertNotSame($id1, $id2);
    }
}
