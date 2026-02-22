<?php

declare(strict_types=1);

namespace Symkit\MailerBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symkit\MailerBundle\Entity\Layout;

/**
 * @extends ServiceEntityRepository<Layout>
 */
final class LayoutRepository extends ServiceEntityRepository
{
    /**
     * @param class-string<Layout> $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = Layout::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
