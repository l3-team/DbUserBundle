<?php

namespace L3\Bundle\DbUserBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository implements UserProviderInterface {

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQueryBuilder('u')
            ->where('u.uid = :username')
            ->setParameter('username', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass(string $class): bool
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }

}
