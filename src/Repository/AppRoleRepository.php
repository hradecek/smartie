<?php

namespace Smartie\Repository;

use Smartie\Entity\AppRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @method AppRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppRole[]    findAll()
 * @method AppRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppRoleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AppRole::class);
    }
}
