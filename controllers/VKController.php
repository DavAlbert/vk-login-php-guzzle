<?php namespace Controllers;

class VKController {
    protected $sessionMiddleware = null;
    protected $vkService = null;

    public function __construct($sessionMiddleware, $vkService)
    {
        $this->sessionMiddleware = $sessionMiddleware;
        $this->vkService = $vkService;
    }

    public function redirect($code) {
        $this->sessionMiddleware->onlyForAnonymous();
        $this->vkService->getAccessToken($code);
    }

}