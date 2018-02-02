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
     * @var array|string|int|bool
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
     * @throws GuzzleException
     */
    public function request(RequestInterface $request) : ResponseInterface
    {
        $responseClass = $request->getResponseClass();

        return new $responseClass($this->rawRequest($request->getMethod(), $request->getUri(), $request->getOptions()));
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed
     */
    public function rawRequest(string $method, string $uri, array $options = [])
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

            // create url
            $url = $this->baseUrl . '/' . $uri . '?apikey=' . $this->apiKey;

            // do request
            $this->httpResponse = $client->request($method, $url, $options);

            // parse response
            $this->response = \GuzzleHttp\json_decode((string)$this->httpResponse->getBody(), true);
        } catch (ExceptionInterface $e) {
            $this->addResponseError($e, 'Symfony Options Resolver Exception');
        } catch (GuzzleException $e) {
            $this->addResponseError($e, 'Guzzle Exception');
        } catch (\InvalidArgumentException $e) {
            $this->addResponseError($e, 'JSON parse error');
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
    public function getHttpResponse() : ?HttpResponseInterface
    {
        return $this->httpResponse;
    }

    /**
     * @return array|bool|int|string
     */
    public function getResponse()
    {
        return $this->response;
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
            RequestOptions::HTTP_ERRORS => true
        ]);
    }

    protected function addResponseError(\Exception $exception, string $title, ?string $detail = null) : void
    {
        if (!isset($this->response['errors'])) {
            $this->response['errors'] = [];
        }

        $error = [
            'title' => $title,
            'detail' => $detail ? : $exception->getMessage(),
            'meta' => [
                'exception' => get_class($exception)
            ]
        ];

        if ($this->httpResponse) {
            $error['status'] = $this->httpResponse->getStatusCode();
        }

        $this->response['errors'][] = $error;
    }
}
