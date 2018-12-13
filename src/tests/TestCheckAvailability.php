<?php

use AgileGeeks\tests\BaseTestCase as BaseTestCase;
use AgileGeeks\EPP\Eurid\Client as Eurid_Client;

class TestCheckAvailability extends BaseTestCase
{
    protected function setUp()
    {
        include('config.php');
        $this->client = new Eurid_Client(
            $host = $config['host'],
            $user = $config['user'],
            $pass = $config['pass'],
            $debug = $config['debug'],
            $port = $config['port'],
            $timeout = $config['timeout'],
            $ssl = $config['ssl'],
            $context = $config['context']
        );
    }

    protected function tearDown()
    {
    }

    public function test_domain_availability()
    {
        $this->client->login();
        $response = $this->client->checkDomains('registry.eu');
        $this->assertArrayHasKey('registry.eu', $response);
        $this->assertEquals('1', $response['registry.eu']);
        $this->client->logout();
    }
}