<?php
namespace AgileGeeks\tests;

use PHPUnit\Framework\TestCase as TestCase;

class BaseTestCase extends TestCase
{
    protected static $generic_contact = array(
        'Ultra Geek',
        '',
        'Some street number and location',
        '',
        '',
        'Bucuresti',
        'Bucuresti',
        '213333',
        'RO',
        '+40.762365542',
        '',
        'offie@agilegeeks.ro'
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
