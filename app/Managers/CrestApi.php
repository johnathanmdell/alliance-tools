<?php
namespace App\Managers;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class CrestApi
{
    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var array
     */
    private $subItems = [];

    /**
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->guzzleClient = new Client(['base_uri' => $uri]);
    }

    /**
     * @param string $uri
     * @return mixed
     */
    public function getItems($uri)
    {
        $response = $this->getDecodedJson($this->getResponse($uri));

        if ($this->hasMultiplePages($response)) {
            return $this->getMultiplePageResponse($uri, $response->pageCount);
        }

        return $this->items = $response->items;
    }

    /**
     * @param string $sub_item_type
     * @return array
     */
    public function getSubItems($sub_item_type)
    {
        $relationalRequest = function () {
            foreach ($this->items as $item) {
                yield new Request('GET', $item->href);
            }
        };

        $relationalPool = new Pool($this->guzzleClient, $relationalRequest(), [
            'concurrency' => 15,
            'fulfilled' => function ($response, $index) use ($sub_item_type) {
                foreach ($this->getDecodedJson($response)->{$sub_item_type} as $subItem) {
                    $subItem->parent = $index;
                    $this->subItems[] = $subItem;
                }
            }
        ]);

        $relationalPool->promise()->wait();

        return $this->subItems;
    }

    /**
     * @param $uri
     * @return ResponseInterface
     */
    private function getResponse($uri)
    {
        return $this->guzzleClient->get($uri);
    }

    /**
     * @param string $response
     * @return bool
     */
    private function hasMultiplePages($response)
    {
        return isset($response->pageCount) && $response->pageCount > 1 ? true : false;
    }

    /**
     * @param $uri
     * @param $page_count
     * @return array
     */
    private function getMultiplePageResponse($uri, $page_count)
    {
        $traversalRequest = function ($page_count) use ($uri) {
            for ($i = 1; $i <= $page_count; $i++) {
                yield new Request('GET', $uri . '?page=' . $i);
            }
        };

        $traversalPool = new Pool($this->guzzleClient, $traversalRequest($page_count), [
            'concurrency' => 15,
            'fulfilled' => function ($response) {
                foreach ($this->getDecodedJson($response)->items as $item) {
                    $this->items[] = $item;
                }
            }
        ]);

        $traversalPool->promise()->wait();

        return $this->items;
    }

    public function getPairedResponse($uri, array $parameters)
    {
        $pairedRequest = function ($parameters) use ($uri) {
            foreach ($parameters as $parameter) {
                yield new Request('GET', sprintf($uri, $parameter));
            }
        };

        $pairedPool = new Pool($this->guzzleClient, $pairedRequest($parameters), [
            'concurrency' => 15,
            'fulfilled' => function ($response, $index) {
                foreach ($this->getDecodedJson($response)->items as $item) {
                    $item->_param = $index;
                    $this->items[] = $item;
                }
            }
        ]);

        $pairedPool->promise()->wait();

        return $this->items;
    }

    /**
     * @param ResponseInterface $response
     * @return object
     */
    private function getDecodedJson($response)
    {
        return json_decode($response->getBody()->getContents());
    }
}