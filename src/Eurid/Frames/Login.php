<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class Login extends Command{

    const TEMPLATE = <<<XML
    <command>
      <login>
        <clID>%s</clID>
        <pw>%s</pw>
        <options>
          <version>1.0</version>
          <lang>en</lang>
        </options>
        <svcs>
          <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
          <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
          <objURI>http://www.eurid.eu/xml/epp/nsgroup-1.1</objURI>
          <objURI>http://www.eurid.eu/xml/epp/keygroup-1.1</objURI>
          <objURI>http://www.eurid.eu/xml/epp/registrarFinance-1.0</objURI>
          <objURI>http://www.eurid.eu/xml/epp/registrarHitPoints-1.0</objURI>
          <objURI>http://www.eurid.eu/xml/epp/registrationLimit-1.1</objURI>
          <objURI>http://www.eurid.eu/xml/epp/dnssecEligibility-1.0</objURI>
          <svcExtension>
                <extURI>http://www.eurid.eu/xml/epp/contact-ext-1.2</extURI>
                  <extURI>http://www.eurid.eu/xml/epp/domain-ext-2.1</extURI>
                  <extURI>urn:ietf:params:xml:ns:secDNS-1.1</extURI>
                  <extURI>http://www.eurid.eu/xml/epp/idn-1.0</extURI>
                  <extURI>http://www.eurid.eu/xml/epp/authInfo-1.1</extURI>
                  <extURI>http://www.eurid.eu/xml/epp/poll-1.2</extURI>
                  <extURI>http://www.eurid.eu/xml/epp/homoglyph-1.0</extURI>
          </svcExtension>
         </svcs>
      </login>
     <clTRID>%s</clTRID>
    </command>
XML;

    function __construct($user,$pass) {
        $this->xml = sprintf(self::TEMPLATE, $user, $pass, $this->clTRID());
    }

    function getResult($dom){
        return True;
    }

}
?>
