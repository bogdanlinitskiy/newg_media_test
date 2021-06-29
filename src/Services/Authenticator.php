<?php

namespace App\Services;

use Exception;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class Authenticator
{
    /** @var  TokenStorageInterface */
    protected TokenStorageInterface $tokenStorage;

    /** @var  AuthenticationManagerInterface */
    protected AuthenticationManagerInterface $authManager;


    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authManager = $authManager;
    }


    public function authenticate($login, $password)
    {
        try {
            $userToken = $this->authManager->authenticate(
                new UsernamePasswordToken($login, $password, 'main', [])
            );

            $authenticatedToken = $this->authManager->authenticate($userToken);

            $this->tokenStorage->setToken($authenticatedToken);

        } catch (AuthenticationException $e) {
            throw new Exception('Wrong login or password');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
