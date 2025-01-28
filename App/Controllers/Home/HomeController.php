<?php

namespace App\Controllers\Home;

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\JsonResponse;
use Core\Controller\BaseController;
use App\Services\AuthService;

class HomeController extends BaseController
{
    private AuthService $auth;

    public function __construct(AuthService $auth)
    {
        parent::__construct();
        $this->auth = $auth;
    }

    public function index(Request $request, Response $response)
    {
        if ($request->wantsJson()) {
            return JsonResponse::success(['message' => 'Welcome to API']);
        }
        
        // Fonction pour obtenir le message de salutation
        $greeting = $this->getGreeting();
        
        return $this->render('home/index', [
            'title' => 'Accueil',
            'greeting' => $greeting,
            'current_time' => date('H:i'),
            'user' => $this->auth->getUser()
        ]);
    }

    private function getGreeting(): string
    {
        $hour = (int)date('H');
        if ($hour >= 6 && $hour < 18) {
            return "Bonjour";
        } elseif ($hour >= 18 && $hour < 22) {
            return "Bonsoir";
        } else {
            return "Bonne nuit";
        }
    }
} 