<?php

use AgileGeeks\tests\BaseTestCase as BaseTestCase;
use AgileGeeks\EPP\Eurid\Client as Eurid_Client;

class TestUpdateDNSSEC extends BaseTestCase
{
    protected function setUp()
    {
        include('config.php');
        $this->domain = 'testdom-'.self::randomnumber(10).'.eu';
        $this->exDate = '';
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
        $this->client->login();
        $registrant = array_merge(self::$generic_contact, array('registrant'));
        $onsite = array_merge(self::$generic_contact, array('onsite'));
        $this->registrant_id = $this->client->createContact(...$registrant);
        $this->onsite_id = $this->client->createContact(...$onsite);
        $this->client->createDomain(
            $domain = $this->domain,
            $period = 5,
            $registrant_cid = $this->registrant_id,
            $contact_tech_cid = "c504538",
            $contact_billing_cid = "c503024",
            $contact_onsite_cid = $this->onsite_id,
            $contact_reseller_cid = "c504525",
            $nameservers = array(
                array('ns1.' . $this->domain, '192.162.16.101'),
                array('ns2.' . $this->domain, '192.162.16.102'),
                array('ns.x.com', null),
            )
        );
    }

    protected function tearDown()
    {
        $res = $this->client->domainInfo($this->domain);
        $this->client->deleteDomain($this->domain, $res->exDate);
        $this->client->logout();
    }

    public function test_update_dnssec()
    {
        $ds_data = (object) array(
            'flags' => '256',
            'protocol' => '3',
            'alg' => '13',
            'pubKey' => 'AwEAAaW+suUu87YF+Hm3gHqyjvQbSSf8JxcP9+pWZGpqQtE1ZXJT9yIgi8hNM0mO7eIGjlHA9cXf/z5HESEA/vHx9F/HsnX7qsXd7dRfsd88VKo4EkB0rPGK4jvw9BskJDioFZUXKPrmLXA/N01FKRZ5MWe5pUgNXnKa0yenTQgN5Paf'
        );
        $this->client->updateDNSSEC($this->domain, array($ds_data), array());
        $response = $this->client->domainInfo($this->domain);

        $this->assertEquals($ds_data->flags, $response->secDNS[0]['flags']);
        $this->assertEquals($ds_data->protocol, $response->secDNS[0]['protocol']);
        $this->assertEquals($ds_data->alg, $response->secDNS[0]['alg']);
        $this->assertEquals($ds_data->pubKey, $response->secDNS[0]['pubKey']);

        $this->client->updateDNSSEC($this->domain, array(), array($ds_data));
        $response = $this->client->domainInfo($this->domain);

        $this->assertEquals('', $response->secDNS);
    }
}