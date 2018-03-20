<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__ . '/Command.php');

class CheckBalance extends Command
{
    const TEMPLATE = <<<XML
    <command>
        <info>
            <registrar:info xmlns:registrar="http://www.eurid.eu/xml/epp/registrarFinance-1.0"/>
        </info>
        <clTRID>%s</clTRID>
  </command>
XML;

    function __construct()
    {
        $this->xml = sprintf(
            self::TEMPLATE,
            $this->clTRID()
        );
    }

    function getResult($dom)
    {
        parent::getResult($dom);

        $resData_node = $dom->getElementsByTagName('resData')->item(0);
        $infData_node = $resData_node->getElementsByTagName('infData')->item(0);
        
        $result = new \stdClass();
        $result->paymentMode = $infData_node->getElementsByTagName('paymentMode')->item(0)->firstChild->textContent;
        $result->availableAmount = $infData_node->getElementsByTagName('availableAmount')->item(0)->firstChild->textContent;
        $result->accountBalance = $infData_node->getElementsByTagName('paymentMode')->item(0)->firstChild->textContent;

        return $result;
    }
}
