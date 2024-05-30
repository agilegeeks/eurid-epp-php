<?php

namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__ . '/Command.php');

class DomainInfo extends Command
{

    const TEMPLATE = <<<XML
    <command>
        <info>
          <domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
            <domain:name>%s</domain:name>
          </domain:info>
          %s
        </info>
        %s
        <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($domain, $authInfo = null, $requestAuthInfo = false)
    {
        $authinfo_section = '';
        if ($authInfo != null) {
            $authinfo_section = "<domain:authInfo><domain:pw>{$authInfo}</domain:pw></domain:authInfo>";
        }

        /* request EPP Auth Code */
        $extension_section = '';
        if ($requestAuthInfo) {
            $extension_section = <<<EOM
        <extension>
          <authInfo:info xmlns:authInfo="http://www.eurid.eu/xml/epp/authInfo-1.1">
            <authInfo:request/>
          </authInfo:info>
        </extension>
EOM;
        }

        $this->xml = sprintf(
            self::TEMPLATE,
            $domain,
            $authinfo_section,
            $extension_section,
            $this->clTRID()
        );
    }

    function getResult($dom)
    {
        parent::getResult($dom);
        $result = new \stdClass();
        $result->contacts = array(
            'registrant' => null,
            'tech' => null,
            'onsite' => null,
            'billing' => null,
            'reseller' => null
        );
        $result->nameservers = array();

        $resData_node = $dom->getElementsByTagName('resData')->item(0);
        $infData_node = $resData_node->getElementsByTagName('infData')->item(0);
        $domain_ns_node = $infData_node->getElementsByTagName('ns')->item(0);
        $extension_node = $dom->getElementsByTagName('extension')->item(0);

        $result->name = $infData_node->getElementsByTagName('name')->item(0)->firstChild->textContent;
        $result->roid = $infData_node->getElementsByTagName('roid')->item(0)->firstChild->textContent;
        $result->status = $infData_node->getElementsByTagName('status')->item(0)->getAttribute('s');
        $result->contacts['registrant'] = $infData_node->getElementsByTagName('registrant')->item(0)->firstChild->textContent;
        $result->clID = $infData_node->getElementsByTagName('clID')->item(0)->firstChild->textContent;
        $result->crID = $infData_node->getElementsByTagName('crID')->item(0)->firstChild->textContent;
        $result->upID = $infData_node->getElementsByTagName('upID')->item(0)->firstChild->textContent;
        $result->crDate = $infData_node->getElementsByTagName('crDate')->item(0)->firstChild->textContent;
        $result->upDate = $infData_node->getElementsByTagName('upDate')->item(0)->firstChild->textContent;
        $result->exDate = $infData_node->getElementsByTagName('exDate')->item(0)->firstChild->textContent;

        /* domain:pw node has the EPP transfer key */
        $hasAuthPW = $infData_node->getElementsByTagName('pw');
        if ($hasAuthPW->length) {
            $result->authPW = $infData_node->getElementsByTagName('pw')->item(0)->firstChild->textContent;

            /* node authInfo:validUntil has the Expiration date for the EPP key */
            $extension_auth_infoData_node = $extension_node->getElementsByTagNameNS('http://www.eurid.eu/xml/epp/authInfo-1.1', 'infData')->item(0);
            $result->authValidUntil = $extension_auth_infoData_node->getElementsByTagName('validUntil')->item(0)->firstChild->textContent;
        }

        $extension_infData_node = $extension_node->getElementsByTagNameNS('http://www.eurid.eu/xml/epp/domain-ext-2.6', 'infData')->item(0);
        $result->onHold = $extension_infData_node->getElementsByTagName('onHold')->item(0)->firstChild->textContent === 'true' ? true : false;
        $result->quarantined = $extension_infData_node->getElementsByTagName('quarantined')->item(0)->firstChild->textContent === 'true' ? true : false;
        $result->suspended = $extension_infData_node->getElementsByTagName('suspended')->item(0)->firstChild->textContent === 'true' ? true : false;
        $result->seized = $extension_infData_node->getElementsByTagName('seized')->item(0)->firstChild->textContent === 'true' ? true : false;
        $result->delayed = $extension_infData_node->getElementsByTagName('delayed')->item(0)->firstChild->textContent === 'true' ? true : false;

        $result->nsgroup = '';
        $nsgroup = $extension_infData_node->getElementsByTagName('nsgroup');

        if ($nsgroup->length > 0) {
            $result->nsgroup = $nsgroup->item(0)->firstChild->textContent;
        }

        $result->delDate = '';
        $deletion_date = $extension_infData_node->getElementsByTagName('deletionDate');
        if ($deletion_date->length > 0) {
            $result->delDate = $deletion_date->item(0)->firstChild->textContent;
        }

        $result->secDNS = '';
        $secDNSInfData = $extension_node->getElementsByTagNameNS('urn:ietf:params:xml:ns:secDNS-1.1', 'infData');

        if ($secDNSInfData->length > 0) {
            $temp = array();

            foreach ($secDNSInfData->item(0)->getElementsByTagName('keyData') as $secDNS) {
                $temp['flags'] = $secDNS->getElementsByTagName('flags')->item(0)->firstChild->textContent;
                $temp['protocol'] = $secDNS->getElementsByTagName('protocol')->item(0)->firstChild->textContent;
                $temp['alg'] = $secDNS->getElementsByTagName('alg')->item(0)->firstChild->textContent;
                $temp['pubKey'] = $secDNS->getElementsByTagName('pubKey')->item(0)->firstChild->textContent;

                $result->secDNS[] = $temp;
            }
        }

        foreach ($extension_infData_node->getElementsByTagName('contact') as $node) {
            $result->contacts[$node->getAttribute('type')] = $node->firstChild->textContent;
        }

        foreach ($infData_node->getElementsByTagName('contact') as $node) {
            $result->contacts[$node->getAttribute('type')] = $node->firstChild->textContent;
        }

        if ($domain_ns_node) {
            foreach ($domain_ns_node->getElementsByTagName('hostAttr') as $node) {
                $nameserver = $node->getElementsByTagName('hostName')->item(0)->firstChild->textContent;
                $ips = array();

                if ($node->getElementsByTagName('hostAddr')) {
                    foreach ($node->getElementsByTagName('hostAddr') as $ip) {
                        $ips[] = $ip->textContent;
                    }
                }

                $result->nameservers[$nameserver] = array('ips' => $ips);
            }
        }

        return $result;
    }
}
