<?php
namespace Loevgaard\Linkmobility;

use Assert\Assert;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Loevgaard\Linkmobility\Payload\Message;

class Client
{
    protected $apiKey;
    protected $baseUrl = 'https://api.linkmobility.dk/v2';

    /**
     * @var GuzzleClientInterface
     */
    protected $httpClient;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function request($method, $uri, $options) {
        $url = $this->baseUrl . $uri;

        $client = $this->getHttpClient();
        $client->request($method, $url, $options);
    }

    /**
     * Resources / Endpoints
     *
     * We use HTTP verbs prepended to the endpoint to indicate what HTTP method is used
     */

    /**
     * @param array $payload
     */
    public function postMessage($payload) {
        if($payload instanceof Message) {
            $payload = $payload->getPayload();
        }
        $this->request('post', '/message.json', [
            'json' => $payload
        ]);
    }

    /*
     * Getters / Setters
     */

    /**
     * @return string
     */
    public function getBaseUrl() : string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * @return Client
     */
    public function setBaseUrl(string $baseUrl) : Client
    {
        Assert::that($baseUrl)->url();

        // when we are building the complete url in the Client::request method we
        // assume that the base url doesn't have a trailing slash
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    /**
     * @return GuzzleClientInterface
     */
    public function getHttpClient()
    {
        if(!$this->httpClient) {
            $this->httpClient = new GuzzleClient();
        }
        return $this->httpClient;
    }

    /**
     * @param GuzzleClientInterface $httpClient
     * @return Client
     */
    public function setHttpClient(GuzzleClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

}
