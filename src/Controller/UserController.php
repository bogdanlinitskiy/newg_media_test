<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Repository\UserRepository;
use App\Services\Authenticator;
use App\Validation\UserApiValidation;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public const ALLOWED_SEARCH_PARAMS = ['email', 'username', 'id', 'limit', 'page'];
    public const ALLOWED_REGISTRATION_PARAMS = ['email', 'username', 'password'];

    private UserRepository $userRepository;
    private UserModel $userModel;
    private UserApiValidation $userApiValidation;
    private Authenticator $authenticator;
    private RequestStack $requestStack;

    public function __construct(
        UserRepository $userRepository,
        UserModel $userModel,
        UserApiValidation $userApiValidation,
        Authenticator $authenticator,
        RequestStack $requestStack
    )
    {
        $this->userRepository = $userRepository;
        $this->userModel = $userModel;
        $this->userApiValidation = $userApiValidation;
        $this->authenticator = $authenticator;
        $this->requestStack = $requestStack;
    }

    public function index()
    {
        return new Response(12312312);
    }

    public function auth(Request $request): Response
    {
        try {
            $params = json_decode($request->getContent(), true);
            $this->authenticator->authenticate($params['username'], $params['password']);

        } catch (Exception $e) {
            return new Response($e->getMessage());
        }
        return new Response('Successful authenticate');
    }

    public function show($id): Response
    {
        return $this->userRepository->getOneById($id);
    }

    public function create(Request $request): Response
    {
        $params = json_decode($request->getContent(), true);

        $validationResult = $this->userApiValidation->validateCreateRequest($params);
        if ($validationResult !== true) {
            return $validationResult;
        }

        $this->checkRegistrationParams($params);
        return $this->userModel->create($params['email'], $params['username'], $params['password']);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function update(Request $request): Response
    {
        $params = json_decode($request->getContent(), true);
        $this->denyAccessUnlessGranted('update', $params);

        $validationResult = $this->userApiValidation->validateUpdateRequest($params);
        if ($validationResult !== true) {
            return $validationResult;
        }

        $this->checkRegistrationParams($params);
        return $this->userModel->update(
            $params['id'],
            $params['email'],
            $params['username'],
            $params['password']
        );
    }

    public function search(Request $request)
    {
        $params = $request->query->all();
        if ($result = $this->checkSearchParams($params) instanceof Response) {
            return $result;
        }

        $result = $this->userRepository->search($params);
        return new Response(json_encode($result));
    }

    public function checkSearchParams($params)
    {
        return $this->checkRequestParams($params, self::ALLOWED_SEARCH_PARAMS);
    }

    public function checkRequestParams($params, $allowedRequestTypes)
    {
        foreach ($params as $param => $value) {
            if (!in_array($param, $allowedRequestTypes)) {
                return new Response(sprintf('Param %s not allowed in search', $param));
            }
        }

        return true;
    }

    public function checkRegistrationParams($params)
    {
        return $this->checkRequestParams($params, self::ALLOWED_REGISTRATION_PARAMS);
    }
}
