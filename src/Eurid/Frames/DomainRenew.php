<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class DomainRenew extends Command {

    const TEMPLATE = <<<XML
    <command>
        <renew>
            <domain:renew xmlns:domain='urn:ietf:params:xml:ns:domain-1.0'>
                <domain:name>%s</domain:name>
                <domain:curExpDate>%s</domain:curExpDate>
                <domain:period unit='%s'>%s</domain:period>
            </domain:renew>
        </renew>
        <clTRID>%s</clTRID>
  </command>
XML;

    function __construct($domain, $period, $curExpDate, $unit='y') {

        $this->xml = sprintf(
            self::TEMPLATE,
            $domain,
            $curExpDate,
            $unit,
            $period,
            $this->clTRID()
        );
    }

    function getResult($dom){
        parent::getResult($dom);
        return (object) array();
    }

}
