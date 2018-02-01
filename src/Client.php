<?php
namespace Loevgaard\Linkmobility;

use Assert\Assert;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use Loevgaard\Linkmobility\Request\RequestInterface;
use Loevgaard\Linkmobility\Response\ResponseInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

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
    protected $baseUrl;

    /**
     * The HTTP client used for making requests
     *
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * Contains the last response object
     *
     * @var HttpResponseInterface|null
     */
    protected $lastHttpResponse;

    public function __construct(string $apiKey, string $baseUrl = 'https://api.linkmobility.dk/v2')
    {
        Assert::that($baseUrl)->url();

        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(RequestInterface $request) : ResponseInterface
    {
        $this->rawRequest($request->getMethod(), $request->getUri(), $request->getOptions());
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function rawRequest(string $method, string $uri, array $options = [])
    {
        $client = $this->getHttpClient();

        // @todo move default options somewhere else
        $options = array_merge($options, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'verify' => false,
            'http_errors' => false,
        ]);
        $this->lastHttpResponse = $client->request($method, $this->baseUrl . $uri.'?apikey='.$this->apiKey, $options);

        return \GuzzleHttp\json_decode((string)$this->lastHttpResponse->getBody());
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient() : ClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = new HttpClient();
        }
        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     * @return Client
     */
    public function setHttpClient(ClientInterface $httpClient) : Client
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Returns the latest response or null if no request was made yet
     *
     * @return HttpResponseInterface|null
     */
    public function getLastHttpResponse() : ?HttpResponseInterface
    {
        return $this->lastHttpResponse;
    }
}
