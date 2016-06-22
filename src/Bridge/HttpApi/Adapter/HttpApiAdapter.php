<?php

namespace Bridge\HttpApi\Adapter;

use GuzzleHttp\Client;
use Transfer\Adapter\SourceAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;

class HttpApiAdapter implements SourceAdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function receive(Request $request)
    {
        $client = new Client();

        $data = $request->getData();

        $url = call_user_func_array('sprintf', array_merge(
            array($data['source']['url']),
            array_map(
                function ($element) {
                    return urlencode($element);
                },
                $data['arguments']
            )
        ));

        $method = 'GET';

        if (isset($data['source']['method'])) {
            $method = $data['source']['method'];
        }

        $response = $client->request($method, $url);

        return new Response((string) $response->getBody());
    }
}
