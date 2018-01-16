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
            
            foreach ($add as $k => $a) {
                $_add .= "<domain:hostAttr>";
                $_add .= "<domain:hostName>".$k."</domain:hostName>";

                if (!empty($a['ips'])) {
                    foreach ($a['ips'] as $ip) {
                        $_add .= '<domain:hostAddr ip="'.$this->detect_ip_version($ip).'">'.$ip.'</domain:hostAddr>';
                    }
                }

                $_add .= "</domain:hostAttr>";
            }

            $_add .= '</domain:ns>
                </domain:add>';
        }

        if (!empty($rem)) {
            $_rem = '<domain:rem>
                        <domain:ns>';
            foreach ($rem as $k => $r) {
                $_rem .= "<domain:hostAttr>";
                $_rem .= "<domain:hostName>".$k."</domain:hostName>";

                if (!empty($r['ips'])) {
                    foreach ($r['ips'] as $ip) {
                        $_rem.= '<domain:hostAddr ip="'.$this->detect_ip_version($ip).'">'.$ip.'</domain:hostAddr>';
                    }
                }

                $_rem .= "</domain:hostAttr>";
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

    private function detect_ip_version($ip)
    {
        if (strpos($ip, '.') > 0) {
            return 'v4';
        } else if (strpos($ip, ':') > 0) {
            return 'v6';
        } else {
            return false;
        }
    }

    function getResult($dom){
        parent::getResult($dom);
        return (object) array();
    }

}
