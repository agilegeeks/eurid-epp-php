<?php
namespace AgileGeeks\EPP\Eurid;

class Frame {
    private $xml;
    private $frame;

    const XML_PREFIX	= '<?xml version=\'1.0\' encoding=\'UTF-8\'?>';
    const TEMPLATE		= '<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">%s</epp>';

    function __construct($frame) {
        $this->frame = $frame;
        $xml = self::XML_PREFIX.PHP_EOL.self::TEMPLATE.PHP_EOL;
        $xml = sprintf($xml,$frame->getXML());
        $this->xml = $xml;
    }

    function getXML(){
        return $this->xml;
    }

    function getResult($dom){
        return $this->frame->getResult($dom);
    }
}
?>
