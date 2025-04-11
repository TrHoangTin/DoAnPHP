<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/oauth.php'; 

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class OAuthService {
    private $providers = [];

    public function __construct() {
        $config = include __DIR__ . '/../config/oauth.php'; 

        $this->providers['google'] = new Google([
            'clientId'     => $config['google']['clientId'],
            'clientSecret' => $config['google']['clientSecret'],
            'redirectUri'  => $config['google']['redirectUri'],
        ]);

        $this->providers['facebook'] = new Facebook([
            'clientId'     => $config['facebook']['clientId'],
            'clientSecret' => $config['facebook']['clientSecret'],
            'redirectUri'  => $config['facebook']['redirectUri'],
            'graphApiVersion' => $config['facebook']['graphApiVersion'],
        ]);
    }

    public function getAuthUrl($providerName) {
        if (!isset($this->providers[$providerName])) {
            throw new Exception("Provider not supported");
        }
        
        return $this->providers[$providerName]->getAuthorizationUrl([
            'scope' => ['email']
        ]);
    }

    public function getUser($providerName, $code) {
        if (!isset($this->providers[$providerName])) {
            throw new Exception("Provider not supported");
        }

        try {
            $token = $this->providers[$providerName]->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            $user = $this->providers[$providerName]->getResourceOwner($token);

            return [
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'provider' => $providerName,
                'provider_id' => $user->getId()
            ];
        } catch (IdentityProviderException $e) {
            throw new Exception("OAuth error: " . $e->getMessage());
        }
    }
}