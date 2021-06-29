<?php

namespace App\Validation;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserApiValidation
{
    private ValidatorInterface $validator;
    private UserRepository $userRepository;

    public function __construct(ValidatorInterface $validator, UserRepository $userRepository)
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function validateCreateRequest($request)
    {
        $constraint = new Assert\Collection([
            'email' => [new Assert\NotBlank(), new Assert\Email()],
            'username' => [new Assert\NotBlank(), new Assert\Length(['min' => 6])],
            'password' => [new Assert\NotBlank(), new Assert\Length(['min' => 6])],
        ]);

        $violations = $this->validator->validate($request, $constraint);

        $violationMessages = [];
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $violationMessages[] = sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage());
        }

        if(count($violationMessages) >= 1) {
            return new Response(json_encode($violationMessages, true));
        }

        if ($this->userRepository->findUserByEmail($request['email']) instanceof User) {
            return new Response(sprintf("User with email '%s' already exists", $request['email']));
        }
        if ($this->userRepository->findUserByUsername($request['username']) instanceof User) {
            return new Response(sprintf("User with username '%s' already exists", $request['username']));
        }

        return true;
    }

    public function validateUpdateRequest($request)
    {
        $constraint = new Assert\Collection([
            'id'       => [new Assert\NotBlank()],
            'email'    => [new Assert\NotBlank(), new Assert\Email()],
            'username' => [new Assert\NotBlank(), new Assert\Length(['min' => 6])],
            'password' => [new Assert\NotBlank(), new Assert\Length(['min' => 6])]
        ]);

        $violations = $this->validator->validate($request, $constraint);

        $violationMessages = [];
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $violationMessages[] = sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage());
        }

        if(count($violationMessages) >= 1) {
            return new Response(json_encode($violationMessages, true));
        }
        $userByEmail = $this->userRepository->findUserByEmail($request['email']);
        $userByUsername = $this->userRepository->findUserByUsername($request['username']);

        if ($userByEmail->getId() !== $request['id'] || $userByUsername->getId() !== $request['id']) {
            return new Response('You cannot modify another user');
        }

        return true;
    }
}
