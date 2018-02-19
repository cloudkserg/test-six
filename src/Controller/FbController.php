<?php
/**
 * Created by PhpStorm.
 * User: dns
 * Date: 16.02.18
 * Time: 17:16
 */

namespace App\Controller;
use App\Service\OAuthService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FbController extends Controller
{
    /**
     * @var OAuthService
     */
    private $service;

    public function __construct(OAuthService $service)
    {
        $this->service = $service;
    }


    /**
     * @Route("/fb/check", name="fb-check")
     */
    public function checkAction(Request $request)
    {
        $state = $request->get('state', null);
        $code = $request->get('code', null);
        if (!isset($state) or !isset($code)) {
            throw new \Exception('not found state or code');
        }

        $user = $this->service->authenticate($state, $code);

        return $this->redirect('/');

    }
}