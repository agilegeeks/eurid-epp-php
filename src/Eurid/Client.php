<?php
namespace AgileGeeks\EPP\Eurid;

use AgileGeeks\EPP\EPP_Client;
use AgileGeeks\EPP\Eurid\Frame;
use AgileGeeks\EPP\Eurid\Response;
use AgileGeeks\EPP\Eurid\Frames\Greeting;
use AgileGeeks\EPP\Eurid\Frames\Login;
use AgileGeeks\EPP\Eurid\Frames\Logout;
use AgileGeeks\EPP\Eurid\Frames\DomainCheck;
use AgileGeeks\EPP\Eurid\Frames\ContactCreate;
use AgileGeeks\EPP\Eurid\Frames\DomainCreate;
use AgileGeeks\EPP\Eurid\Frames\ContactInfo;
use AgileGeeks\EPP\Eurid\Frames\DomainInfo;


require_once (__DIR__.'/Frames/autoload.php');
require_once (__DIR__.'/Frame.php');
require_once (__DIR__.'/../EPP/Client.php');
require_once(__DIR__.'/Response.php');


class Eurid_Client extends EPP_Client {

	private $connected;
	private $logged_in;
	private $user;
    private $result;


	var $debug;

	var $greeting;

	/**
	* @param string $host
	* @param string $user
	* @param string $pass
	* @param boolean $debug
	* @param integer $port
	* @param integer timeout
	* @param boolean $ssl
	* @param resource $context
	* @throws Net_EPP_Exception
	*/
	function __construct($host=NULL, $user=NULL, $pass=NULL, $debug=false, $port=700, $timeout=1, $ssl=true, $context=NULL) {
		$this->connected	= false;
		$this->logged_in	= false;
		$this->debug		= $debug;
		$this->user		= $user;
        $this->pass		= $pass;

		if ($host) $this->connect($host, $port, $timeout, $ssl, $context);
		//if ($user && $pass) $this->login($user, $pass);
	}

    function getResult(){
        return $this->result;
    }

    function greeting(){
        $command = new Greeting();
        $frame = new Frame($command);
        return $this->request($frame);
    }

	function login() {
        $this->debug("attempting login");
        $command = new Login($this->user, $this->pass);
        $frame = new Frame($command);
        return $this->request($frame);
	}

	function checkDomains($domains) {
        $this->debug("checking domains");
        $command = new DomainCheck($domains);
        $frame = new Frame($command);
        return $this->request($frame);
	}

	function checkHosts($host) {
	}

	function checkContacts($contacts) {
	}

	function domainInfo($domain, $authInfo=NULL) {
        $this->debug("getting contact info");
        $command = new DomainInfo($domain,$authInfo);
        $frame = new Frame($command);
        return $this->request($frame);
	}

	function hostInfo($host, $authInfo=NULL) {
	}

	function contactInfo($contact, $authInfo=NULL) {
        $this->debug("getting contact info");
        $command = new ContactInfo($contact);
        $frame = new Frame($command);
        return $this->request($frame);
	}

	function domainTransferQuery($domain) {
	}

	function domainTransferCancel($domain) {
	}

	function domainTransferRequest($domain, $authInfo, $period, $unit='y') {
	}

	function domainTransferApprove($domain) {
	}

	function domainTransferReject($domain) {
	}

	function contactTransferQuery($contact) {
	}

	function contactTransferCancel($contact) {
	}

	function contactTransferRequest($contact, $authInfo) {
	}

	function contactTransferApprove($contact) {
	}

	function contactTransferReject($contact) {
	}

	function createDomain($domain,
                        $period,
                        $registrant_cid,
                        $contact_tech_cid,
                        $contact_billing_cid,
                        $contact_onsite_cid,
                        $contact_reseller_cid,
                        $nameservers=array()) {
        $this->debug("creating domain");
        $command = new DomainCreate($domain,
                            $period,
                            $registrant_cid,
                            $contact_tech_cid,
                            $contact_billing_cid,
                            $contact_onsite_cid,
                            $contact_reseller_cid,
                            $nameservers);
        $frame = new Frame($command);
        return $this->request($frame);

	}

	function createHost($host) {
	}

	function createContact($name,
                        $organization,
                        $street1,
                        $street2,
                        $street3,
                        $city,
                        $postal_code,
                        $country_code,
                        $phone,
                        $fax,
                        $email,
                        $contact_type='registrant') {
        $this->debug("creating contact");
        $command = new ContactCreate($name,
                            $organization,
                            $street1,
                            $street2,
                            $street3,
                            $city,
                            $postal_code,
                            $country_code,
                            $phone,
                            $fax,
                            $email,
                            $contact_type);
        $frame = new Frame($command);
        return $this->request($frame);
	}

	function updateDomain($domain, $add, $rem, $chg) {
	}

	function updateContact($contact, $add, $rem, $chg) {
	}

	function updateHost($host, $add, $rem, $chg) {
	}

	function deleteDomain($domain) {
	}

	function deleteHost($host) {
	}

	function deleteContact($contact) {
	}

	function renewDomain($domain, $period, $unit='y') {
	}

	function request($frame) {
		$this->sendFrame($frame->getXML());
        $dom = $this->getFrame();
        $this->result = new Response($dom);
        $response = $frame->getResult($dom);
		return $response;
	}

	function logout() {
		$this->debug("logging out");
        $command = new Logout($this->user, $this->pass);
        $frame = new Frame($command);
        return $this->request($frame);
	}

	function connect($host, $port=700, $timeout=1, $ssl=true, $context=NULL) {
		$this->debug("attempting to connect to %s:%d", $host, $port);
		$dom = parent::connect($host, $port, $timeout, $ssl, $context);
		$this->debug("connected OK");
		$this->connected = true;
        //return $this->greeting();

	}

	function getFrame() {
		$xml = parent::getFrame();
        $this->xml = $xml;
		foreach (explode("\n", str_replace('><', ">\n<", trim($xml))) as $line) $this->debug("S: %s", $line);
        $dom = new \DOMDocument;
        $dom->loadXML($this->xml);
        return $dom;

	}


	function sendFrame($xml) {
		foreach (explode("\n", str_replace('><', ">\n<", trim($xml))) as $line) $this->debug("C: %s", $line);
		return parent::sendFrame($xml);
	}

	protected function debug() {
		if (!$this->debug) return true;
		$args = func_get_args();
		error_log(vsprintf(array_shift($args), $args));
	}

	function __destruct() {
		if ($this->logged_in) $this->logout();
		$this->debug("disconnecting from server");
		$this->disconnect();
	}

}