<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symkit\MailerBundle\Entity\EmailLog;

/**
 * @extends ServiceEntityRepository<EmailLog>
 */
class EmailLogRepository extends ServiceEntityRepository
{
    /**
     * @param class-string<EmailLog> $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = EmailLog::class)
    {
        parent::__construct($registry, $entityClass);
    }

    public function findOneByMessageId(string $messageId): ?EmailLog
    {
        $result = $this->createQueryBuilder('l')
            ->where('l.messageId = :messageId')
            ->setParameter('messageId', $messageId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result instanceof EmailLog ? $result : null;
    }
}
