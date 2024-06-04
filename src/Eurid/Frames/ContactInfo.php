<?php

namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__ . '/Command.php');

class ContactInfo extends Command
{

    const TEMPLATE = <<<XML
    <command>
        <info>
          <contact:info xmlns:contact='urn:ietf:params:xml:ns:contact-1.0'>
            <contact:id>%s</contact:id>
          </contact:info>
        </info>
        <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($contact_id)
    {
        $this->xml = sprintf(
            self::TEMPLATE,
            $contact_id,
            $this->clTRID()
        );
    }

    function getResult($dom)
    {
        parent::getResult($dom);
        //var_dump($dom->saveXML());exit;
        $result = new \stdClass();
        $resData_node = $dom->getElementsByTagName('resData')->item(0);
        $infData_node = $resData_node->getElementsByTagName('infData')->item(0);
        $postalInfo_node = $infData_node->getElementsByTagName('postalInfo')->item(0);
        $extension_node = $dom->getElementsByTagName('extension')->item(0);
        $extension_infData_node = $extension_node->getElementsByTagNameNS('http://www.eurid.eu/xml/epp/contact-ext-1.3', 'infData')->item(0);

        $result->contact_id = $infData_node->getElementsByTagName('id')->item(0)->firstChild->textContent;
        $result->roid = $infData_node->getElementsByTagName('roid')->item(0)->firstChild->textContent;
        $result->status = $infData_node->getElementsByTagName('status')->item(0)->getAttribute('s');
        $result->voice = $infData_node->getElementsByTagName('voice')->item(0)->firstChild->textContent;
        $result->email = $infData_node->getElementsByTagName('email')->item(0)->firstChild->textContent;
        $result->clID = $infData_node->getElementsByTagName('clID')->item(0)->firstChild->textContent;
        $result->crID = $infData_node->getElementsByTagName('crID')->item(0)->firstChild->textContent;
        $result->crDate = $infData_node->getElementsByTagName('crDate')->item(0)->firstChild->textContent;
        $result->upDate = $infData_node->getElementsByTagName('upDate')->item(0)->firstChild->textContent;


        $result->name = $postalInfo_node->getElementsByTagName('name')->item(0)->firstChild->textContent;
        if ($postalInfo_node->getElementsByTagName('org')->length > 0) {
            $result->org = $postalInfo_node->getElementsByTagName('org')->item(0)->firstChild->textContent;
        } else {
            $result->org = null;
        }
        $result->city = $postalInfo_node->getElementsByTagName('city')->item(0)->firstChild->textContent;

        if ($postalInfo_node->getElementsByTagName('sp')->length > 0) {
            $result->sp = $postalInfo_node->getElementsByTagName('sp')->item(0)->firstChild->textContent;
        }

        $result->pc = $postalInfo_node->getElementsByTagName('pc')->item(0)->firstChild->textContent;
        $result->cc = $postalInfo_node->getElementsByTagName('cc')->item(0)->firstChild->textContent;
        $result->street = array();
        foreach ($postalInfo_node->getElementsByTagName('street') as $node) {
            $result->street[] = $node->nodeValue;
        }
        $result->type = $extension_infData_node->getElementsByTagName('type')->item(0)->firstChild->textContent;

        if ($extension_infData_node->getElementsByTagName('naturalPerson')->length > 0) {
            $result->natural_person = $extension_infData_node->getElementsByTagName('naturalPerson')->item(0)->firstChild->textContent;
        } else {
            $result->natural_person = null;
        }

        if ($extension_infData_node->getElementsByTagName('countryOfCitizenship')->length > 0) {
            $result->country_of_citizenship = $extension_infData_node->getElementsByTagName('countryOfCitizenship')->item(0)->firstChild->textContent;
        } else {
            $result->country_of_citizenship = null;
        }

        return $result;
    }
}
