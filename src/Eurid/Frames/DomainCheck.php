<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class DomainCheck extends Command{

    const TEMPLATE = <<<XML
    <command>
        <check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
          <domain:check>
            %s
          </domain:check>
        </check>
    </command>
XML;

    function __construct($domains) {
        $domain_names_template='';
        if (is_string($domains)){
            $domain_names_template = "<domain:name>{$domains}</domain:name>";
        }else{
            if (is_array($domains)){
                foreach ($domains as $dn) {
                    $domain_names_template.="<domain:name>{$dn}</domain:name>".PHP_EOL;
                }
            }
        }

        $this->xml = sprintf(self::TEMPLATE, $domain_names_template);
    }

    function getResult($dom){
        parent::getResult($dom);
        $availability = array();

        $chkData_node = $dom->getElementsByTagName('chkData')->item(0);
        $dn_nodes = $chkData_node->getElementsByTagName('name');
        foreach ($dn_nodes as $node) {
            $availability[$node->nodeValue] = $node->getAttribute('avail') === 'true'? true: false;
        }
        return $availability;
    }

}
?>
