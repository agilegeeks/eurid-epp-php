<?php
namespace AgileGeeks\tests;

use PHPUnit\Framework\TestCase as TestCase;

class BaseTestCase extends TestCase
{
    protected $generic_contact = array(
        'name' => 'Ultra Geek',
        'organization' => '',
        'street1' => 'Some street number and location',
        'street2' => '',
        'street3' => '',
        'city' => 'Bucuresti',
        'state_province' => 'Bucuresti',
        'postal_code' => '213333',
        'country_code' => 'RO',
        'phone' => '+40.762365542',
        'fax' => '',
        'email' => 'offie@agilegeeks.ro',
        'contact_type' => 'registrant'
    );

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    protected static function randomstring($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    protected static function randomnumber($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
