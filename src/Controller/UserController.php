<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Exception\ServerException;
use App\Form\MessageType;
use App\Form\UserType;
use App\Service\AuthService;
use App\Service\MessageService;
use App\Service\UserService;
use Doctrine\Common\Annotations\Annotation\Required;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @var MessageService
     */
    private $service;
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(UserService $service, AuthService $authService)
    {
        $this->service = $service;
        $this->authService = $authService;
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws ServerException
     * @throws \App\Exception\AuthException
     */
    public function editAction(Request $request)
    {
        $user = $this->authService->getAuthUser();

        $form = $this->createForm(UserType::class, $user, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new ServerException((string) $form->getErrors(true, false));
        }

        $this->service->saveUser($form->getData(), $user);
        return $this->redirectToRoute('default');
    }

}
