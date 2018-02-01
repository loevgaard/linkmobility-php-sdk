<?php
namespace Loevgaard\Linkmobility\Request;

interface RequestInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * Must return the HTTP verb for this request, i.e. GET, POST, PUT
     *
     * @return string
     */
    public function getMethod() : string;

    /**
     * Must return the URI for the request with a leading slash, i.e. /messages.json
     *
     * @return string
     */
    public function getUri() : string;

    /**
     * Must return the body which is being sent as json
     *
     * @return array
     */
    public function getBody() : array;

    /**
     * Must return the class to where the response is handed over. It must implement the ResponseInterface
     *
     * @return string
     */
    public function getResponseClass() : string;

    /**
     * Must return the options for this request. If there are none, return [] (empty array)
     *
     * @return array
     */
    public function getOptions() : array;

    /**
     * Must validate the input of the request
     * This is called before sending the request
     * Must throw an exception if the validation fails
     */
    public function validate() : void;
}
