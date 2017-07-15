<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Frames\Command;

require_once(__DIR__.'/Command.php');

class Greeting extends Command{

    const TEMPLATE		= '<hello/>';

    function __construct() {
        $this->xml = self::TEMPLATE;
    }

    function getResult($dom){
        return True;
    }

}
?>
