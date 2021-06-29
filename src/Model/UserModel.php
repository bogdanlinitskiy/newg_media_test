<?php

namespace App\Model;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserModel
{
    private EntityManagerInterface $emi;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $emi, UserPasswordHasherInterface $passwordHasher)
    {
        $this->emi = $emi;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param $email
     * @param $username
     * @param $password
     *
     * @return Response
     */
    public function create($email, $username, $password): Response
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setCreatedAt(new DateTime());

        $salt = md5(uniqid());
        $user->setSalt($salt);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        try {
            $this->emi->persist($user);
            $this->emi->flush();
            return new Response(
                sprintf(
                    'User successfully created. Your id: %s',
                    $user->getId()
                )
            );
        } catch (Exception $exception) {
            return new Response('Exception while creating user');
        }
    }

    /**
     * @param $id
     * @param string|null $email
     * @param string|null $username
     * @param string|null $password
     *
     * @return Response
     */
    public function update($id, string $email, string $username, ?string $password = null): Response
    {
        /** @var User $user */
        $user = $this->emi->find(User::class, $id);
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setUpdatedAt(new DateTime());

        if (!is_null($password)) {
            $salt = md5(uniqid());
            $user->setSalt($salt);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        }

        try {
            $this->emi->persist($user);
            $this->emi->flush();
            return new Response('User successfully update');
        } catch (Exception $exception) {
            return new Response('Exception while updating user');
        }
    }
}
