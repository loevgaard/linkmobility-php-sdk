<?php declare(strict_types=1);
namespace Loevgaard\Linkmobility;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @throws \Symfony\Component\OptionsResolver\Exception\ExceptionInterface
     */
    public function testGettersSetters()
    {
        $httpClient = new GuzzleClient();

        $client = new Client('api key');
        $client->setHttpClient($httpClient);

        $this->assertSame($httpClient, $client->getHttpClient());

        // tests lazy load of client
        $client = new Client('api key');
        $this->assertInstanceOf(GuzzleClient::class, $client->getHttpClient());
    }

    /**
     * @throws \Symfony\Component\OptionsResolver\Exception\ExceptionInterface
     */
    public function testRequest()
    {
        // tests last response
        $returnObj = ['key' => 'val'];
        $response = new Response(200, [], json_encode($returnObj));
        $handler = HandlerStack::create(new MockHandler([$response]));

        $httpClient = new GuzzleClient(['handler' => $handler]);
        $client = new Client('api key');
        $client->setHttpClient($httpClient);
        $rawResponse = $client->rawRequest('get', '/test.json');

        $this->assertSame($returnObj, $rawResponse);
        $this->assertEquals($response, $client->getHttpResponse());
    }

    /*
    public function testPostMessage()
    {
        $responseArray = [
            "stat"  => [
                "sendtime" => "17-12-2014 08:42:24",
                "buffered" => 0,
                "received" => 1,
                "rejected" => 1
            ],
            "details" => [
                "sendtime" => "17-12-2014 08:42:24",
                "batchid" => "310701",
                "state" => "DONE"
            ],
            "status" => 200
        ];

        // convert to \stdClass
        $responseObject = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($responseArray));

        $mock = new MockHandler([
            new Response(200, [], \GuzzleHttp\json_encode($responseObject)),
        ]);
        $handler = HandlerStack::create($mock);

        $guzzleClient = new GuzzleClient(['handler' => $handler]);
        $payload = new Message();
        $payload
            ->setRecipients([
                '+4511223344'
            ])
            ->setSender('sender')
            ->setMessage('test message')
        ;
        $client = new Client('api key');
        $client->setHttpClient($guzzleClient);
        $res = $client->postMessage($payload);

        $this->assertInstanceOf(BatchStatusResponse::class, $res);
    }
    */
}
