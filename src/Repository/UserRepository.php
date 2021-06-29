<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;

class UserRepository extends EntityRepository
{
    public function getOneById($id): Response
    {
        /** @var User $user */
        $user = $this->createQueryBuilder('u')
            ->select('partial u.{id, email, username, createdAt}')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();

        if ($user instanceof User) {
            return new Response(json_encode($user));
        }

        return new Response(sprintf("User with id '%s' not exist", $id));
    }

    public function search($params)
    {
        $offset = !isset($params['page']) || $params['page'] <= 1
            ? 0
            : ($params['page'] - 1) * $params['limit'];

        $qb = $this->createQueryBuilder('u')
        ->select('partial u.{id, email, username, createdAt}');

        if (isset($params['email'])) {
            $qb
                ->andWhere('u.email = :email')
                ->setParameter('email', $params['email']);
        }

        if (isset($params['username'])) {
            $qb
                ->andWhere('u.username = :username')
                ->setParameter('username', $params['username']);
        }

        $qb
            ->setMaxResults($params['limit'] ?: 10)
            ->setFirstResult($offset)
            ->orderBy('u.createdAt', 'DESC');

        var_dump($qb->getQuery()->getArrayResult());
        return $qb->getQuery()->getArrayResult();
    }

    public function findUserByEmail($email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findUserByUsername($username): ?User
    {
        return $this->findOneBy(['username' => $username]);
    }
}
