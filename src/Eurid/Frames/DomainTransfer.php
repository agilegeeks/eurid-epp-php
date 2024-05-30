<?php

namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__ . '/Command.php');

class DomainTransfer extends Command
{

    const TEMPLATE = <<<XML
    <command>
        <transfer op="request">
          <domain:transfer xmlns:domain='urn:ietf:params:xml:ns:domain-1.0'>
            <domain:name>%s</domain:name>
            <domain:period unit='%s'>%s</domain:period>
            <domain:authInfo>
              <domain:pw>%s</domain:pw>
            </domain:authInfo>
          </domain:transfer>
        </transfer>
        <extension>
            <domain-ext:transfer xmlns:domain='urn:ietf:params:xml:ns:domain-1.0' xmlns:domain-ext='http://www.eurid.eu/xml/epp/domain-ext-2.6'>
                <domain-ext:request>
                    <domain-ext:registrant>%s</domain-ext:registrant>
                    <domain-ext:contact type='billing'>%s</domain-ext:contact>
                    <domain-ext:contact type='tech'>%s</domain-ext:contact>
                </domain-ext:request>
            </domain-ext:transfer>
        </extension>
        <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($domain, $pw, $period, $cid, $billing, $tech, $unit = 'y')
    {

        $this->xml = sprintf(
            self::TEMPLATE,
            $domain,
            $unit,
            $period,
            $pw,
            $cid,
            $billing,
            $tech,
            $this->clTRID()
        );
    }

    function getResult($dom)
    {
        parent::getResult($dom);
        return (object) array();
    }
}
