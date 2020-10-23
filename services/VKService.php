<?php namespace Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VKService {
    protected $appId = null;
    protected $redirectUrl = null;
    protected $scopes = null;
    protected $secretKey = null;

    public function __construct($appId, $redirectUrl, $scopes, $secretKey)
    {
        $this->appId = $appId;
        $this->redirectUrl = $redirectUrl;
        $this->scopes = $scopes;
        $this->secretKey = $secretKey;
    }

    public function getOAuth2URL() {
        return sprintf('https://oauth.vk.com/authorize?client_id=%s&display=page&redirect_uri=%s&scope=%s&response_type=code&v=5.107',
            $this->appId, $this->redirectUrl, $this->scopes);
    }

    public function getAccessToken($code) {
        $client = new Client(['base_uri' => 'https://oauth.vk.com/access_token']);
        try {
            $response = $client->request('GET', '?client_id='.$this->appId.'&client_secret='.$this->secretKey.'&code='.$code.'&redirect_uri='.$this->redirectUrl);
            $jsonResponse = json_decode($response->getBody()->getContents());
            $_SESSION['accessToken'] = $jsonResponse->{'access_token'};
            $_SESSION['userId'] = $jsonResponse->{'user_id'};
            header('Location: /welcome');
        } catch (GuzzleException $e) {
            header('Location: /login');
        }
    }

    public function getFirstAndLastName() {
        $accessToken = $_SESSION['accessToken'];
        $userId = $_SESSION['userId'];
        if (!isset($accessToken) || !isset($userId)) {
            session_destroy();
            header('Location: /login');
        }
        $client = new Client(['base_uri' => 'https://api.vk.com/method/users.get']);
        try {
            $response = $client->request('GET', sprintf('?user_ids=%s&access_token=%s&v=5.21&fields=photo_200', $userId, $accessToken));
            $jsonResponse = json_decode($response->getBody()->getContents());

            $firstName = $jsonResponse->{'response'}[0]->{'first_name'};
            $lastName = $jsonResponse->{'response'}[0]->{'last_name'};
            $photo = $jsonResponse->{'response'}[0]->{'photo_200'};

            require 'views/WelcomeView.php';
        } catch (GuzzleException $e) {
            session_destroy();
            header('Location: /login');
        }
    }
}
