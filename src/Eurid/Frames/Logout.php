<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class Logout extends Command{

    const TEMPLATE = <<<XML
    <command>
        <logout/>
        <clTRID>%s</clTRID>
    </command>
XML;

    function __construct() {
        $this->xml = sprintf(self::TEMPLATE, $this->clTRID());
    }

    function getResult($dom){
        return True;
    }
}
?>
