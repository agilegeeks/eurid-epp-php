<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class DomainUpdateNS extends Command {

    const TEMPLATE = <<<XML
    <command>
        <update>
            <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                <domain:name>%s</domain:name>
                %s
                %s
            </domain:update>
        </update>
    <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($domain, $add = array(), $rem = array()) {
        $_add = '';
        $_rem = '';

        if (!empty($add)) {
            $_add = '<domain:add>
                <domain:ns>';
            
            foreach ($add as $a) {
                $_add .= "<domain:hostAttr>
                    <domain:hostName>$a</domain:hostName>
                </domain:hostAttr>";
            }

            $_add .= '</domain:ns>
                </domain:add>';
        }

        if (!empty($rem)) {
            $_rem = '<domain:rem>
                        <domain:ns>';
            foreach ($rem as $r) {
                $_rem .= "<domain:hostAttr>
                            <domain:hostName>$r</domain:hostName>
                        </domain:hostAttr>";
            }

            $_rem .= '</domain:ns>
                </domain:rem>';
        }

        $this->xml = sprintf(
            self::TEMPLATE,
            $domain,
            $_add,
            $_rem,
            $this->clTRID()
        );
    }

    function getResult($dom){
        parent::getResult($dom);
        return (object) array();
    }

}
