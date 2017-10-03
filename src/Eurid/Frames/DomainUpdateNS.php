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

    function __construct($domain, $add = '', $rem = '') {
        if (!empty($add)) {
            $add = "<domain:add>
                        <domain:ns>
                            <domain:hostAttr>
                                <domain:hostName>$add</domain:hostName>
                            </domain:hostAttr>
                        </domain:ns>
                    </domain:add>";
        }

        if (!empty($rem)) {
            $rem = "<domain:rem>
                        <domain:ns>
                            <domain:hostAttr>
                                <domain:hostName>$rem</domain:hostName>
                            </domain:hostAttr>
                        </domain:ns>
                    </domain:rem>";
        }

        $this->xml = sprintf(
            self::TEMPLATE,
            $domain,
            $add,
            $rem,
            $this->clTRID()
        );
    }

    function getResult($dom){
        parent::getResult($dom);
        return (object) array();
    }

}
