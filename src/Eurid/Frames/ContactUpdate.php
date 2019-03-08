<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__ . '/Command.php');

class ContactUpdate extends Command
{
    const TEMPLATE = <<<XML
    <command>
        <update xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
            <contact:update>
                <contact:id>%s</contact:id>
                    <contact:chg>
                        <contact:postalInfo type='loc'>
                            <contact:name>%s</contact:name>
                            <contact:org>%s</contact:org>
                            <contact:addr>
                                <contact:street>%s</contact:street>
                                <contact:street>%s</contact:street>
                                <contact:street>%s</contact:street>
                                <contact:city>%s</contact:city>
                                <contact:sp>%s</contact:sp>
                                <contact:pc>%s</contact:pc>
                                <contact:cc>%s</contact:cc>
                            </contact:addr>
                        </contact:postalInfo>
                        <contact:voice>%s</contact:voice>
                        <contact:fax>%s</contact:fax>
                        <contact:email>%s</contact:email>
                    </contact:chg>
                </contact:update>
            </update>
            <extension>
                <contact-ext:update xmlns:contact-ext='http://www.eurid.eu/xml/epp/contact-ext-1.2'>
                    <contact-ext:chg/>
                </contact-ext:update>
            </extension>
        <clTRID>%s</clTRID>
    </command>
XML;

    function __construct(
        $id,
        $name,
        $organization,
        $street1,
        $street2,
        $street3,
        $city,
        $state_province,
        $postal_code,
        $country_code,
        $phone,
        $fax,
        $email
    )
    {
        $this->xml = sprintf(
            self::TEMPLATE,
            $id,
            $name,
            $organization,
            $street1,
            $street2,
            $street3,
            $city,
            $state_province,
            $postal_code,
            $country_code,
            $phone,
            $fax,
            $email,
            $this->clTRID()
        );
    }

    function getResult($dom)
    {
        parent::getResult($dom);
        return (object) array();
    }
}
