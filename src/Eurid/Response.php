<?php
namespace AgileGeeks\EPP\Eurid;

class Response {
    private $dom;

    function __construct($dom) {
        $this->dom = $dom;
    }

    function getDOM(){
        return $this->dom;
    }

    function code() {
        return $this->dom->getElementsByTagName('result')->item(0)->getAttribute('code');
    }

    function message() {
        return $this->dom->getElementsByTagName('msg')->item(0)->firstChild->textContent;
    }

    function detailed_message() {
        $dm='';
        try {
            $extValue_node = $this->dom->getElementsByTagName('extValue')->item(0);
            $dm = $extValue_node->getElementsByTagName('reason')->item(0)->firstChild->textContent;
        } catch (Exception $e) {

        }
        return $dm;
    }

    function svTRID(){
        return $this->dom->getElementsByTagName('svTRID')->item(0)->firstChild->textContent;
    }
}
