<?php

use AgileGeeks\tests\BaseTestCase as BaseTestCase;
use AgileGeeks\EPP\Eurid\Client as Eurid_Client;

class TestCheckAvailability extends BaseTestCase
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
        $this->registrant_id = $this->client->createContact(...self::$generic_contact);
        $this->onsite_id = $this->client->createContact(...self::$generic_contact);
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
        $this->client->deleteDomain($this->domain, $this->exDate);
        $this->client->logout();
    }

    public function test_domain_availability()
    {
        // Check a domain that is available
        $available_domain = self::randomstring(21).'.eu';
        $response = $this->client->checkDomains($available_domain);
        $this->assertArrayHasKey($available_domain, $response);
        $this->assertEquals('0', $response[$available_domain]);

        // Check a domain that is not available
        $response = $this->client->checkDomains($this->domain);
        $this->assertArrayHasKey($this->domain, $response);
        $this->assertEquals('1', $response[$this->domain]);

        // Check multiple domains availability
        $response = $this->client->checkDomains($this->domain.','.$available_domain);
        $this->assertArrayHasKey($available_domain, $response);
        $this->assertArrayHasKey($this->domain, $response);
        $this->assertEquals('0', $response[$available_domain]);
        $this->assertEquals('1', $response[$this->domain]);

        // Check that an 'invalid domain' error is returned
        $response = $this->client->checkDomains(self::randomString(21));
        print_r($response);
        $this->assertEquals('1', $response[$this->domain]);
    }
}
