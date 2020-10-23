<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Services\VKService;

use Middlewares\SessionMiddleware;

use Controllers\VKController;
use Controllers\UserController;

session_start();

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request = preg_replace("|/*(.+?)/*$|", "\\1", $_SERVER["REQUEST_URI"]);
$uri = explode('/', $request);
$url = parse_url($uri[0]);

require_once 'services/VKService.php';
require_once 'middlewares/SessionMiddleware.php';

require_once 'controllers/UserController.php';
require_once 'controllers/VKController.php';

$vkService = new VKService($_ENV['APP_ID'], $_ENV['REDIRECT'], $_ENV['SCOPES'], $_ENV['SECRET_KEY']);
$sessionMiddleware = new SessionMiddleware();

$Controllers = [
    'user' => new UserController($sessionMiddleware, $vkService),
    'vk' => new VKController($sessionMiddleware, $vkService)
];


switch ($url['path']) {
    case 'login':
        $Controllers['user']->login();
        break;
    case 'welcome':
        $Controllers['user']->welcome();
        break;
    case 'logout':
        $Controllers['user']->logout();
        break;
    case 'redirect':
        $code = explode('=', $url['query'])[1];
        $Controllers['vk']->redirect($code);
        break;
    default:
        $Controllers['user']->login();
}