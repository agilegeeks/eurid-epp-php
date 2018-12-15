<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class DomainAddDNSSEC extends Command {

    const TEMPLATE = <<<XML
    <command>
        <update>
            <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                <domain:name>%s</domain:name>
            </domain:update>
        </update>
        <extension>
            <secDNS:update xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1">
                <secDNS:add>
                    %s
                </secDNS:add>
            </secDNS:update>
        </extension>
    <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($domain, $add) {
        $_add = '';
        
        foreach ($add as $a) {
            $_add .= "<secDNS:keyData>";
            $_add .= "<secDNS:flags>$a->flags</secDNS:flags>";
            $_add .= "<secDNS:protocol>$a->protocol</secDNS:protocol>";
            $_add .= "<secDNS:alg>$a->alg</secDNS:alg>";
            $_add .= "<secDNS:pubKey>$a->pubKey</secDNS:pubKey>";
            $_add .= "</secDNS:keyData>";
        }

        $this->xml = sprintf(
            self::TEMPLATE,
            $domain,
            $_add,
            $this->clTRID()
        );
    }

    function getResult($dom){
        parent::getResult($dom);
        return (object) array();
    }

}
