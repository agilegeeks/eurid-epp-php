<?php

use AgileGeeks\tests\BaseTestCase as BaseTestCase;
use AgileGeeks\EPP\Eurid\Client as Eurid_Client;

class TestCheckAvailability extends BaseTestCase
{
    protected function setUp()
    {
        include('config.php');
        $this->domain = 'testdom-'.self::randomnumber(10).'.eu';
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

    public function test_domain_availability()
    {
        // Check a domain that is available
        $available_domain = self::randomstring(21).'.eu';
        $response = $this->client->checkDomains($available_domain);
        $this->assertArrayHasKey($available_domain, $response);
        $this->assertEquals(true, $response[$available_domain]);

        // Check a domain that is not available
        $response = $this->client->checkDomains($this->domain);
        $this->assertArrayHasKey($this->domain, $response);
        $this->assertEquals(false, $response[$this->domain]);

        // Check multiple domains availability
        $response = $this->client->checkDomains(array($this->domain, $available_domain));
        $this->assertArrayHasKey($available_domain, $response);
        $this->assertArrayHasKey($this->domain, $response);
        $this->assertEquals(true, $response[$available_domain]);
        $this->assertEquals(false, $response[$this->domain]);
    }
}
