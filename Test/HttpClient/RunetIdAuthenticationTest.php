<?php

namespace RunetId\Client\Test\HttpClient;

use Http\Discovery\MessageFactoryDiscovery;
use phpmock\MockBuilder;
use PHPUnit\Framework\TestCase;
use RunetId\Client\HttpClient\RunetIdAuthentication;

class RunetIdAuthenticationTest extends TestCase
{
    public function test()
    {
        $key = 'key';
        $secret = 'secret';
        $time = time();

        (new MockBuilder())
            ->setNamespace('RunetId\Client\HttpClient')
            ->setName('time')
            ->setFunction(function () use ($time) {
                return $time;
            })
            ->build()
            ->enable();

        $auth = new RunetIdAuthentication($key, $secret);

        $request = MessageFactoryDiscovery::find()
            ->createRequest('GET', '/', ['Apikey' => 'apikey', 'Timestamp' => 0, 'Hash' => 'hash']);

        $request = $auth->authenticate($request);

        $this->assertSame($key, $request->getHeaderLine('Apikey'));
        $this->assertSame((string) $time, $request->getHeaderLine('Timestamp'));
        $this->assertSame(substr(md5($key.$time.$secret), 0, 16), $request->getHeaderLine('Hash'));
    }
}