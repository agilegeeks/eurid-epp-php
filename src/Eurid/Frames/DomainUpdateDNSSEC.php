<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class DomainUpdateDNSSEC extends Command {

    const TEMPLATE = <<<XML
    <command>
        <update>
            <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                <domain:name>%s</domain:name>
            </domain:update>
        </update>
        <extension>
            <secDNS:update xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1">
                %s
            </secDNS:update>
        </extension>
    <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($domain, $add = array(), $rem = array()) {
        $_str = '';

        if (count($add) > 0) {
            $_str = "<secDNS:add>";

            foreach ($add as $a) {
                $_str .= "<secDNS:keyData>";
                $_str .= "<secDNS:flags>$a->flags</secDNS:flags>";
                $_str .= "<secDNS:protocol>$a->protocol</secDNS:protocol>";
                $_str .= "<secDNS:alg>$a->alg</secDNS:alg>";
                $_str .= "<secDNS:pubKey>$a->pubKey</secDNS:pubKey>";
                $_str .= "</secDNS:keyData>";
            }

            $_str .= "</secDNS:add>";
        } else if (count($rem) > 0) {
            $_str = "<secDNS:rem>";

            foreach ($rem as $r) {
                $_str .= "<secDNS:keyData>";
                $_str .= "<secDNS:flags>$r->flags</secDNS:flags>";
                $_str .= "<secDNS:protocol>$r->protocol</secDNS:protocol>";
                $_str .= "<secDNS:alg>$r->alg</secDNS:alg>";
                $_str .= "<secDNS:pubKey>$r->pubKey</secDNS:pubKey>";
                $_str .= "</secDNS:keyData>";
            }

            $_str .= "</secDNS:rem>";
        }

        $this->xml = sprintf(
            self::TEMPLATE,
            $domain,
            $_str,
            $this->clTRID()
        );
    }

    function getResult($dom){
        parent::getResult($dom);
        return (object) array();
    }

}
