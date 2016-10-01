<?php
namespace App\Extensions;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use GuzzleHttp\ClientInterface;

class CrestProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://login.eveonline.com/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://login.eveonline.com/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokens($code)
    {
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';

        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                'Accept' => 'application/json'
            ],
            $postKey => $this->getTokenFields($code),
        ]);

        return $this->parseTokens($response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_add(parent::getTokenFields($code), 'grant_type', 'authorization_code');
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://login.eveonline.com/oauth/verify', [
            'headers' => ['Authorization' => 'Bearer ' . $token],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['CharacterID'],
            'name' => $user['CharacterName']
        ]);
    }

    /**
     * @return $this
     */
    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $tokens = $this->getAccessTokens($this->getCode());

        $user = $this->mapUserToObject(array_merge(
                $this->getUserByToken($tokens['access_token']),
                ['RefreshToken' => $tokens['refresh_token']]
            )
        );

        return $user->setToken($tokens['access_token']);
    }

    /**
     * @param $body
     * @return array
     */
    protected function parseTokens($body)
    {
        $jsonResponse = json_decode($body, true);

        return [
            'access_token' => $jsonResponse['access_token'],
            'refresh_token' => $jsonResponse['refresh_token']
        ];
    }
}