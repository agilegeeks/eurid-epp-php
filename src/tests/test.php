<?php
use AgileGeeks\EPP\Eurid\Client as Eurid_Client;

require ('../Client.php');

$host = 'epp.tryout.registry.eu';
$port = 700;
$timeout = 10;
$ssl = true;
$user = 'xxxxx';
$pass = 'xxxxx';
$debug = true;


$client = new Eurid_Client(
                            $host=$host,
                            $user=$user,
                            $pass=$pass,
                            $debug=$debug,
                            $port=$port,
                            $timeout=$timeout,
                            $ssl=$ssl,
                            $context=NULL);
//$client->greeting();exit;

//$client->login();
// $response = $client->checkDomains(array('test.eu','someotherdomain.eu'));
// print_r($response);
//var_dump($client->getResult());
//$client->logout();


$client->login();
$registrant_id = $client->createContact(
                    $name = 'Radu Boncea',
                    $organization ='',
                    $street1 = 'Some street number and location',
                    $street2 = '',
                    $street3 = '',
                    $city = 'Bucuresti',
                    $postal_code = '213333',
                    $country_code = 'RO',
                    $phone = '+40.762365542',
                    $fax = '',
                    $email = 'radu@rotld.ro',
                    $contact_type='registrant'
);
echo $registrant_id."\n";


// $contact_data = $client->contactInfo($registrant_id);
// var_dump($contact_data);


$onsite_id = $client->createContact(
                    $name = 'Radu Boncea',
                    $organization ='JUMP SRL',
                    $street1 = 'Some street number and location',
                    $street2 = '',
                    $street3 = '',
                    $city = 'Bucuresti',
                    $postal_code = '213333',
                    $country_code = 'RO',
                    $phone = '+40.762365542',
                    $fax = '',
                    $email = 'radu@rotld.ro',
                    $contact_type='onsite'
);
echo $onsite_id."\n";

$domain_name = 'test-jump-1411201707.eu';
try {
    $result = $client->createDomain(
                            $domain=$domain_name,
                            $period=5,
                            $registrant_cid=$registrant_id,
                            $contact_tech_cid="c504538",
                            $contact_billing_cid="c503024",
                            $contact_onsite_cid=$onsite_id,
                            $contact_reseller_cid="c504525",
                            $nameservers=array(
                                                array('ns1.'.$domain_name,'192.162.16.101'),
                                                array('ns2.'.$domain_name,'192.162.16.102'),
                                                array('ns.x.com',null),
                                            )
                            );
    echo $result->crDate."\n";
    echo $result->exDate."\n";

} catch (Exception $e) {
    echo $e->getMessage()."\n";
    print $e->getCode()."\n";
}

try {
    $result = $client->domainInfo($domain = $domain_name);

} catch (Exception $e) {
    echo $e->getMessage()."\n";
    print $e->getCode()."\n";
}

$expire_date = $result->exDate;
var_dump($expire_date);

try {
    $result = $client->deleteDomain($domain_name, $expire_date);
    //var_dump($result);

} catch (Exception $e) {
    echo $e->getMessage()."\n";
    print $e->getCode()."\n";
}
//var_dump($result);

// $domain_name = 'test-jump-5555.eu';
// try {
//     $result = $client->renewDomain($domain_name, 1, '2022-11-01', $unit='y');
//     var_dump($result);

// } catch (Exception $e) {
//     echo $e->getMessage()."\n";
//     print $e->getCode()."\n";
// }


$client->logout();
 ?>
