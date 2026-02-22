<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symkit\MailerBundle\Entity\Email;

/**
 * @extends ServiceEntityRepository<Email>
 */
final class EmailRepository extends ServiceEntityRepository
{
    /**
     * @param class-string<Email> $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = Email::class)
    {
        parent::__construct($registry, $entityClass);
    }

    public function getBySlug(string $slug): ?Email
    {
        $result = $this->createQueryBuilder('e')
            ->where('e.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();

        return $result instanceof Email ? $result : null;
    }
}
