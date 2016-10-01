<?php
namespace App\Extensions\CrestGuzzler;

use AllianceTools\User\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

abstract class Resource
{
    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $base_uri = 'https://crest-tq.eveonline.com';

    /**
     * @var array
     */
    protected $arguments = [];

    public function __construct(array $arguments)
    {
        $this->guzzleClient = new Client();
        $this->arguments = $arguments;
    }

    /**
     * @param int|null $resource_id
     * @return mixed
     */
    protected function buildResourceUri($resource_id = null)
    {
        $resource_id = is_null($resource_id) ? $resource_id : $resource_id . '/';

        return call_user_func_array('sprintf', array_merge([$this->base_uri . $resource_id], $this->arguments));
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array|null $body
     * @return string
     */
    protected function request($method, $uri, array $body = null)
    {
        try {
            return $this->parseResponse($this->guzzleClient->send(
                new Request($method, $uri, $this->getHeaders(), $this->getBody($body))));
        } catch(RequestException $exception) {
            return $this->parseExceptionResponse($exception);
        }
    }

    /**
     * @return string
     */
    protected function getUserAccessToken()
    {
        $expiresOn = new Carbon($this->getUser()->character->expires_on);
        if ($expiresOn->gt(new Carbon()) === false) {
            return $this->getRefreshedUserAccessToken();
        }

        return $this->getUser()->character->access_token;
    }

    /**
     * @return string
     */
    protected function getRefreshedUserAccessToken()
    {
        $jsonResponse = json_decode($this->guzzleClient->send(
            new Request('POST', 'https://login.eveonline.com/oauth/token?grant_type=refresh_token&' .
                'refresh_token=' . $this->getUser()->character->refresh_token,
                ['Authorization' => 'Basic ' . base64_encode(env('CREST_CLIENT_ID') . ':' . env('CREST_CLIENT_SECRET'))]
            ))->getBody()->getContents());

        $this->getUser()->character->update([
            'access_token' => $jsonResponse->access_token,
            'expires_on' => date('Y-m-d G:i:s', strtotime('now') + $jsonResponse->expires_in),
            'refresh_token' => $jsonResponse->refresh_token
        ]);

        return $this->getUserAccessToken();
    }

    /**
     * @param ResponseInterface $response
     * @return string
     * @throws Exception
     */
    protected function parseResponse(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param RequestException $exception
     * @return bool
     */
    protected function parseExceptionResponse(RequestException $exception)
    {
        if ($exception->hasResponse()) {
            //dd($exception->getResponse()->getBody()->getContents());
            return false;
        }
    }

    /**
     * @param array|null $body
     * @return array
     */
    protected function getBody(array $body = null)
    {
        return is_null($body) ? $body : json_encode($body);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->user;
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return is_null($this->user) ? [] : ['Authorization' =>
            'Bearer ' . $this->getUserAccessToken()];
    }
}