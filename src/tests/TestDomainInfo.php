<?php

use AgileGeeks\tests\BaseTestCase as BaseTestCase;
use AgileGeeks\EPP\Eurid\Client as Eurid_Client;

class TestDomainInfo extends BaseTestCase
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
        $this->registrant_id = $this->client->createContact(
            $name = 'Ultra Geek',
            $organization = '',
            $street1 = 'Some street number and location',
            $street2 = '',
            $street3 = '',
            $city = 'Bucuresti',
            $state_province = 'Bucuresti',
            $postal_code = '213333',
            $country_code = 'RO',
            $phone = '+40.762365542',
            $fax = '',
            $email = 'offie@agilegeeks.ro',
            $contact_type = 'registrant'
        );
        $this->onsite_id = $this->client->createContact(
            $name = 'Radu Boncea',
            $organization = 'JUMP SRL',
            $street1 = 'Some street number and location',
            $street2 = '',
            $street3 = '',
            $city = 'Bucuresti',
            $state_province = 'Bucuresti',
            $postal_code = '213333',
            $country_code = 'RO',
            $phone = '+40.762365542',
            $fax = '',
            $email = 'radu@rotld.ro',
            $contact_type = 'onsite'
        );
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

    public function test_domain_info()
    {
        $response = $this->client->domainInfo($this->domain);
        $this->assertEquals($this->domain, $response->name);
        $this->exDate = $response->exDate;
    }
}