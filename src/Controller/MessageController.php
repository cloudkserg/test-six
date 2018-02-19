<?php

namespace App\Controller;

use App\Entity\Message;
use App\Exception\ServerException;
use App\Form\MessageType;
use App\Service\AuthService;
use App\Service\MessageService;
use Doctrine\Common\Annotations\Annotation\Required;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    /**
     * @var MessageService
     */
    private $service;
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(MessageService $service, AuthService $authService)
    {
        $this->service = $service;
        $this->authService = $authService;
    }


    /**
     * @Route("/comment", name="comment")
     */
    public function indexAction()
    {
        // replace this line with your own code!
        return $this->render('@Maker/demoPage.html.twig', [ 'path' => str_replace($this->getParameter('kernel.project_dir').'/', '', __FILE__) ]);
    }

    /**
     * @Route("/messages/add", name="message_add")
     *
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(MessageType::class, new Message(), [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new ServerException((string) $form->getErrors(true, false));

        }

        $this->service->saveMessage($form->getData(), $this->authService->getAuthUser());
        return $this->redirectToRoute('default');

    }

    /**
     * @Route("/messages/{id}/edit", name="message_edit")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws ServerException
     * @throws \App\Exception\AuthException
     */
    public function editAction(Request $request, Message $message)
    {
        $form = $this->createForm(MessageType::class, $message, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new ServerException('Что-то пошло не так');

        }

        $this->service->saveMessage($form->getData(), $this->authService->getAuthUser());
        return $this->redirectToRoute('default');
    }
}
