<?php namespace Controllers;

class UserController {
    protected $sessionMiddleware = null;
    protected $vkService = null;

    public function __construct($sessionMiddleware, $vkService)
    {
        $this->sessionMiddleware = $sessionMiddleware;
        $this->vkService = $vkService;
    }

    public function login() {
        $this->sessionMiddleware->onlyForAnonymous();
        $oauth2Url = $this->vkService->getOAuth2URL();
        require 'views/LoginView.php';
    }

    public function welcome() {
        $this->sessionMiddleware->onlyForLoggedIn();
        $this->vkService->getFirstAndLastName();
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
    }
}