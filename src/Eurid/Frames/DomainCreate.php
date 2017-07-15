<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class DomainCreate extends Command{

    const TEMPLATE = <<<XML
      <command>
        <create>
          <domain:create xmlns:domain='urn:ietf:params:xml:ns:domain-1.0'>
            <domain:name>%s</domain:name>
            <domain:period unit='y'>%s</domain:period>
            %s
            <domain:registrant>%s</domain:registrant>
            <domain:contact type='billing'>%s</domain:contact>
            %s
            <domain:authInfo>
              <domain:pw> </domain:pw>
            </domain:authInfo>
          </domain:create>
        </create>
        %s
        <clTRID>%s</clTRID>
      </command>
XML;

    function __construct($domain,
                        $period,
                        $registrant_cid,
                        $contact_tech_cid=null,
                        $contact_billing_cid,
                        $contact_onsite_cid=null,
                        $contact_reseller_cid=null,
                        $nameservers=array()) {
        $domain_ns = '';
        if (sizeof($nameservers)>0){
            $domain_ns = "<domain:ns>".PHP_EOL;
            foreach ($nameservers as $ns) {
                $domain_ns .= "<domain:hostAttr>".PHP_EOL;
                $domain_ns .= "<domain:hostName>{$ns[0]}</domain:hostName>".PHP_EOL;
                if ($ns[1]!=null){
                    $domain_ns .= "<domain:hostAddr>{$ns[1]}</domain:hostAddr>".PHP_EOL;
                }
                $domain_ns .= "</domain:hostAttr>".PHP_EOL;
            }
            $domain_ns .= "</domain:ns>".PHP_EOL;

        }

        $contact_tech = '';
        if ($contact_tech_cid!=null){
            $contact_tech = "<domain:contact type='tech'>{$contact_tech_cid}</domain:contact>";
        }

        $contact_extension = '';
        if ($contact_onsite_cid!=null || $contact_reseller_cid!=null){
            $contact_extension = "<extension><domain-ext:create xmlns:domain-ext='http://www.eurid.eu/xml/epp/domain-ext-2.1'>";
            if ($contact_onsite_cid!=null){
                $contact_extension.= "<domain-ext:contact type='onsite'>{$contact_onsite_cid}</domain-ext:contact>";
            }
            if ($contact_reseller_cid!=null){
                $contact_extension.= "<domain-ext:contact type='reseller'>{$contact_reseller_cid}</domain-ext:contact>";
            }
            $contact_extension .= "</domain-ext:create></extension>";
        }


        $this->xml = sprintf(self::TEMPLATE,
                            $domain,
                            $period,
                            $domain_ns,
                            $registrant_cid,
                            $contact_billing_cid,
                            $contact_tech,
                            $contact_extension,
                            $this->clTRID()
                            );
    }

    function getResult($dom){
        parent::getResult($dom);
        //echo($dom->saveXML());
        $result = new \stdClass();
        $creData_node = $dom->getElementsByTagName('creData')->item(0);
        $result->crDate = $creData_node->getElementsByTagName('crDate')->item(0)->firstChild->textContent;
        $result->exDate = $creData_node->getElementsByTagName('exDate')->item(0)->firstChild->textContent;
        return $result;
    }

}
?>
