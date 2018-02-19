<?php

namespace App\Controller;

use App\Form\MessageType;
use App\Form\UserType;
use App\Service\AuthService;
use App\Service\MessageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;
    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * DefaultController constructor.
     */
    public function __construct(AuthService $authService, MessageService $messageService)
    {
        $this->authService = $authService;
        $this->messageService = $messageService;
    }


    /**
     * @Route("/", name="default")
     */
    public function indexAction()
    {
        $user = $this->authService->getAuthUser();
        $messages = $this->messageService->getMessages();

        // replace this line with your own code!
        return $this->render(
            'default/index.html.twig',
            [
                'form' => $this->createForm(MessageType::class)->createView(),
                'userForm' => $this->createForm(UserType::class)->createView(),
                'user' => $user,
                'messages' => $messages
            ]
        );
    }
}
