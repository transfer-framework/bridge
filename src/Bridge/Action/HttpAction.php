<?php

namespace Bridge\Action;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

/**
 * Action where execution method is pre-implemented with an HTTP call using the Guzzle library.
 */
class HttpAction extends AbstractAction
{
    /**
     * @var string HTTP method
     */
    private $method;

    /**
     * @var string URL
     */
    private $url;

    /**
     * @var array Option collection to be passed to Guzzle
     */
    private $options;

    /**
     * @param string $name    Action name
     * @param string $method  HTTP method
     * @param string $url     URL
     * @param array  $options Option collection to be passed to Guzzle
     */
    public function __construct($name, $method, $url, $options = array())
    {
        parent::__construct($name);

        $this->method = $method;
        $this->url = $url;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($arguments = array())
    {
        $client = new Client();

        $options = $this->options;
        if (array_key_exists(0, $arguments)) {
            $options = array_merge($options, $arguments[0]);
        }

        $request = $client->createRequest($this->method, $this->url, $options);

        $response = $client->send($request);

        return $response;
    }
}
