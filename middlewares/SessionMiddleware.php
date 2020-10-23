<?php namespace Middlewares;

class SessionMiddleware {
    public function onlyForLoggedIn() {
        $accessToken = $_SESSION['accessToken'];

        if (!isset($accessToken)) {
            echo 'You cant visit this page, because you must be logged in.';
            die;
        }

        return $accessToken;
    }

    public function onlyForAnonymous() {
        $accessToken = $_SESSION['accessToken'];

        if (isset($accessToken)) {
            echo 'Please logout to see this page.';
            die;
        }
    }
}