<?php
use AgileGeeks\EPP\Eurid\Client as Eurid_Client;

require ('../Client.php');

$host = 'epp.tryout.registry.eu';
$port = 700;
$timeout = 10;
$ssl = true;
$user = 'XXXXXX';
$pass = 'XXXXXXXXX';
$debug = true;
$client = new Eurid_Client(
    $host = $host,
    $user = $user,
    $pass = $pass,
    $debug = $debug,
    $port = $port,
    $timeout = $timeout,
    $ssl = $ssl,
    $context = NULL
);
$client->login();
$domain_name = 'test-XXXXX-1111.eu';

try {
    //$result = $client->updateNameservers($domain_name, [], ['ns1.tlh.ro', 'ns2.tlh.ro']);
    $result = $client->updateNameservers($domain_name, ['ns1.tlh.ro', 'ns2.tlh.ro'], []);
    var_dump($result);
} catch (Exception $e) {
    echo $e->getMessage()."\n";
    print $e->getCode()."\n";
}

$client->logout();
