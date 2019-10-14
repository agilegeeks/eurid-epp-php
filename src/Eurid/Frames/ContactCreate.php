<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class ContactCreate extends Command{

  const TEMPLATE = <<<XML
  <command>
      <create>
        <contact:create xmlns:contact='urn:ietf:params:xml:ns:contact-1.0'>
          <contact:id>AUTO</contact:id>
          <contact:postalInfo type='loc'>
            <contact:name>%s</contact:name>
            <contact:org>%s</contact:org>
            <contact:addr>
              <contact:street>%s</contact:street>
              <contact:street>%s</contact:street>
              <contact:street>%s</contact:street>
              <contact:city>%s</contact:city>
              <contact:sp>%s</contact:sp>
              <contact:pc>%s</contact:pc>
              <contact:cc>%s</contact:cc>
            </contact:addr>
          </contact:postalInfo>
          <contact:voice>%s</contact:voice>
          <contact:fax>%s</contact:fax>
          <contact:email>%s</contact:email>
          <contact:authInfo>
            <contact:pw></contact:pw>
          </contact:authInfo>
        </contact:create>
      </create>
      <extension>
        <contact-ext:create xmlns:contact-ext='http://www.eurid.eu/xml/epp/contact-ext-1.2'>
          <contact-ext:type>%s</contact-ext:type>
          <contact-ext:lang>en</contact-ext:lang>
        </contact-ext:create>
      </extension>
      <clTRID>%s</clTRID>
    </command>
XML;

  function __construct($name,
    $organization,
    $street1,
    $street2,
    $street3,
    $city,
    $state_province,
    $postal_code,
    $country_code,
    $phone,
    $fax,
    $email,
    $contact_type)
  {
    $this->xml = sprintf(self::TEMPLATE,
    htmlentities($name),
    htmlentities($organization),
    htmlentities($street1),
    htmlentities($street2),
    htmlentities($street3),
      $city,
      $state_province,
      $postal_code,
      $country_code,
      $phone,
      $fax,
      $email,
      $contact_type,
      $this->clTRID()
    );
  }

  function getResult($dom){
    parent::getResult($dom);
    $creData_node = $dom->getElementsByTagName('creData')->item(0);
    $contact_id = $creData_node->getElementsByTagName('id')->item(0)->firstChild->textContent;
    return $contact_id;
  }
}
