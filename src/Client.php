<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility;

use Assert\Assert;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Loevgaard\Linkmobility\Request\RequestInterface;
use Loevgaard\Linkmobility\Response\ResponseInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    protected $httpResponse;

    /**
     * @var array
     */
    protected $response;

    public function __construct(string $apiKey, string $baseUrl = 'https://api.linkmobility.dk/v2')
    {
        Assert::that($baseUrl)->url();

        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ExceptionInterface
     */
    public function request(RequestInterface $request) : ResponseInterface
    {
        $responseClass = $request->getResponseClass();
        return new $responseClass($this->rawRequest($request->getMethod(), $request->getUri(), $request->getBody(), $request->getOptions()));
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $body
     * @param array $options
     * @return array
     * @throws ExceptionInterface
     */
    public function rawRequest(string $method, string $uri, array $body = [], array $options = []) : array
    {
        try {
            // reset responses
            $this->response = [];
            $this->httpResponse = null;

            // get http client
            $client = $this->getHttpClient();

            // resolve options
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver);
            $options = $resolver->resolve($options);

            if (!empty($body)) {
                // the body will always override any other data sent
                $options['json'] = $body;
            }

            // create url
            $url = $this->baseUrl . $uri . '?apikey=' . $this->apiKey;

            // do request
            $this->httpResponse = $client->request($method, $url, $options);

            // parse response
            $this->response = \GuzzleHttp\json_decode((string)$this->httpResponse->getBody(), true);
        } catch (GuzzleException $e) {
            $this->setResponseError($e);
        } catch (\InvalidArgumentException $e) {
            $this->setResponseError($e);
        }

        return $this->response;
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
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * @return array
     */
    public function getResponse() : array
    {
        return (array)$this->response;
    }

    protected function configureOptions(OptionsResolver $resolver) : void
    {
        $refl = new \ReflectionClass(RequestOptions::class);
        $requestOptions = array_values($refl->getConstants());
        $resolver->setDefined($requestOptions);

        $resolver->setDefaults([
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ],
            RequestOptions::CONNECT_TIMEOUT => 30,
            RequestOptions::TIMEOUT => 120,
            RequestOptions::HTTP_ERRORS => false
        ]);
    }

    /**
     * Linkmobility always formats their errors like this, so we mimic this
     *
     * @param string|\Exception $error
     * @param int $statusCode
     */
    protected function setResponseError($error, int $statusCode = 500) : void
    {
        if ($error instanceof \Exception) {
            $error = $error->getMessage();
        }

        $this->response['message'] = (string)$error;
        $this->response['status'] = $statusCode;
    }
}
