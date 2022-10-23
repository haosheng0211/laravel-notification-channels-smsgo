<?php

namespace NotificationChannels\SMSGo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SMSGo
{
    protected $client;

    protected $username;

    protected $password;

    public function __construct(Client $client, array $config)
    {
        $this->client = $client;
        $this->username = data_get($config, 'username');
        $this->password = data_get($config, 'password');
    }

    /**
     * @throws GuzzleException
     */
    public function send(SMSGoMessage $message, string $to): array
    {
        $query = [
            'rtype'    => 'json',
            'username' => $this->username,
            'password' => $this->password,
            'dstaddr'  => $to,
            'smbody'   => $message->content,
            'encoding' => $message->encoding,
        ];

        if ($message->delay) {
            $query['dlvtime'] = $message->delay->format('Y-m-d H:i:s');
        }

        $response = $this->client->get('https://www.smsgo.com.tw/sms_gw/sendsms.aspx', [
            'query' => $query,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
