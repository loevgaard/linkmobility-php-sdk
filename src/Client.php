<?php
namespace Loevgaard\Linkmobility;

use Assert\Assert;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Psr\Http\Message\ResponseInterface;
use Loevgaard\Linkmobility\Payload\Message as MessagePayload;

class Client
{
    /**
     * The API key used for making requests
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The base url used for making requests
     *
     * @var string
     */
    protected $baseUrl = 'https://api.linkmobility.dk/v2';

    /**
     * The HTTP client used for making requests
     *
     * @var GuzzleClientInterface
     */
    protected $httpClient;

    /**
     * Contains the last response object
     *
     * @var ResponseInterface
     */
    protected $lastResponse;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed
     */
    public function request(string $method, string $uri, array $options)
    {
        $client = $this->getHttpClient();
        $options = array_merge($options, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'verify' => false
        ]);
        $this->lastResponse = $client->request($method, $this->baseUrl . $uri.'?apikey='.$this->apiKey, $options);

        return \GuzzleHttp\json_decode((string)$this->lastResponse->getBody());
    }

    /**
     * Resources / Endpoints
     *
     * We use HTTP verbs prepended to the endpoint to indicate what HTTP method is used
     */

    /**
     * Will create a new message
     *
     * @param MessagePayload $payload
     * @return \stdClass
     */
    public function postMessage(MessagePayload $payload) : \stdClass
    {
        return $this->request('post', '/message.json', [
            'json' => $payload->getPayload()
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
    public function getHttpClient() : GuzzleClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = new GuzzleClient();
        }
        return $this->httpClient;
    }

    /**
     * @param GuzzleClientInterface $httpClient
     * @return Client
     */
    public function setHttpClient(GuzzleClientInterface $httpClient) : Client
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Returns the latest response
     *
     * @return ResponseInterface
     */
    public function getLastResponse() : ResponseInterface
    {
        return $this->lastResponse;
    }
}
