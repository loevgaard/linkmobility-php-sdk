<?php
namespace Loevgaard\Linkmobility;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp;
use Loevgaard\Linkmobility\Payload\Message;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testGettersSetters()
    {
        $httpClient = new GuzzleClient();
        $baseUrl = 'http://www.example.com';

        $client = new Client('api key');
        $client->setHttpClient($httpClient)
            ->setBaseUrl($baseUrl);

        $this->assertSame($httpClient, $client->getHttpClient());
        $this->assertEquals($baseUrl, $client->getBaseUrl());

        // tests lazy load of client
        $client = new Client('api key');
        $this->assertInstanceOf(GuzzleClient::class, $client->getHttpClient());

        // tests last response
        $returnObj = new \stdClass();
        $returnObj->data = 'data';
        $response = new Response(200, [], GuzzleHttp\json_encode($returnObj));
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);

        $httpClient = new GuzzleClient(['handler' => $handler]);
        $client = new Client('api key');
        $client->setHttpClient($httpClient);
        $payload = new Message();
        $payload
            ->setRecipients([
                '+4511223344'
            ])
            ->setSender('sender')
            ->setMessage('test message')
        ;
        $client->postMessage($payload);

        $this->assertEquals($response, $client->getLastResponse());
    }
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
        $responseObject = GuzzleHttp\json_decode(GuzzleHttp\json_encode($responseArray));

        $mock = new MockHandler([
            new Response(200, [], GuzzleHttp\json_encode($responseObject)),
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

        $this->assertEquals($responseObject, $res);
    }
}
