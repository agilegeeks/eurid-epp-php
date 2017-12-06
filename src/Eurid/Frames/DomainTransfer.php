<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class DomainTransfer extends Command {

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
        <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($domain, $pw, $period, $unit = 'y') {

        $this->xml = sprintf(
            self::TEMPLATE,
            $domain,
            $unit,
            $period,
            $pw,
            $this->clTRID()
        );
    }

    function getResult($dom){
        parent::getResult($dom);
        return (object) array();
    }

}
