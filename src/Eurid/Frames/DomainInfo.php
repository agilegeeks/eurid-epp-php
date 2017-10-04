<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class DomainInfo extends Command{

    const TEMPLATE = <<<XML
    <command>
        <info>
          <domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
            <domain:name>%s</domain:name>
          </domain:info>
          %s
        </info>
        <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($domain, $authInfo=null) {
        $authinfo_section = '';
        if($authInfo!=null){
            $authinfo_section = "<domain:authInfo><domain:pw>{$authInfo}</domain:pw></domain:authInfo>";
        }

        $this->xml = sprintf(self::TEMPLATE,
                            $domain,
                            $authinfo_section,
                            $this->clTRID()
                            );
    }

    function getResult($dom){
        parent::getResult($dom);
        $result = new \stdClass();
        $result->contacts = array(
            'registrant'=>null,
            'tech'=>null,
            'onsite'=>null,
            'billing'=>null,
            'reseller'=>null
        );
        $result->nameservers=array();

        $resData_node = $dom->getElementsByTagName('resData')->item(0);
        $infData_node = $resData_node->getElementsByTagName('infData')->item(0);
        $domain_ns_node = $infData_node->getElementsByTagName('ns')->item(0);
        $extension_node = $dom->getElementsByTagName('extension')->item(0);
        $extension_infData_node = $extension_node->getElementsByTagName('infData')->item(0);

        $result->name = $infData_node->getElementsByTagName('name')->item(0)->firstChild->textContent;
        $result->roid = $infData_node->getElementsByTagName('roid')->item(0)->firstChild->textContent;
        $result->status = $infData_node->getElementsByTagName('status')->item(0)->getAttribute('s');
        $result->contacts['registrant'] = $infData_node->getElementsByTagName('registrant')->item(0)->firstChild->textContent;
        $result->clID = $infData_node->getElementsByTagName('clID')->item(0)->firstChild->textContent;
        $result->crID = $infData_node->getElementsByTagName('crID')->item(0)->firstChild->textContent;
        $result->upID = $infData_node->getElementsByTagName('upID')->item(0)->firstChild->textContent;
        $result->crDate = $infData_node->getElementsByTagName('crDate')->item(0)->firstChild->textContent;
        $result->upDate = $infData_node->getElementsByTagName('upDate')->item(0)->firstChild->textContent;
        $result->exDate = $infData_node->getElementsByTagName('exDate')->item(0)->firstChild->textContent;


        $result->onHold = $extension_infData_node->getElementsByTagName('onHold')->item(0)->firstChild->textContent === 'true'? true: false;
        $result->quarantined = $extension_infData_node->getElementsByTagName('quarantined')->item(0)->firstChild->textContent === 'true'? true: false;
        $result->suspended = $extension_infData_node->getElementsByTagName('suspended')->item(0)->firstChild->textContent === 'true'? true: false;
        $result->seized = $extension_infData_node->getElementsByTagName('seized')->item(0)->firstChild->textContent === 'true'? true: false;
        $result->delayed = $extension_infData_node->getElementsByTagName('delayed')->item(0)->firstChild->textContent === 'true'? true: false;

        foreach ($extension_infData_node->getElementsByTagName('contact') as $node) {
            $result->contacts[$node->getAttribute('type')] = $node->firstChild->textContent;
        }
        foreach ($infData_node->getElementsByTagName('contact') as $node) {
            $result->contacts[$node->getAttribute('type')] = $node->firstChild->textContent;
        }

        if ($domain_ns_node) {
            foreach ($domain_ns_node->getElementsByTagName('hostAttr') as $node) {
                $nameserver = $node->getElementsByTagName('hostName')->item(0)->firstChild->textContent;
                if ($node->getElementsByTagName('hostAddr')->length>0){
                    $ip = $node->getElementsByTagName('hostAddr')->item(0)->firstChild->textContent;
                }else{
                    $ip = '';
                }
                if ($ip==null) $ip = '';
                $result->nameservers[$nameserver] = $ip;
            }
        }
        
        return $result;
    }

}
?>
