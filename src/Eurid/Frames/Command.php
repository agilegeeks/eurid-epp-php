<?php
namespace AgileGeeks\EPP\Eurid\Frames;

use AgileGeeks\EPP\Eurid\Eurid_Exception;
use AgileGeeks\EPP\Eurid\Response;


require_once(__DIR__.'/../Exception.php');
require_once(__DIR__.'/../Response.php');

abstract class Command {

    protected $xml = null;
    protected $svTRID;

    function getXML(){
        return $this->xml;
    }

    /*
	* generate a client transaction ID
	* @return string
	*/
	function clTRID() {
		$clTRID = base_convert(
			hash('sha256', uniqid()),
			16,
			36
		);
        return $clTRID;
	}

    function getResult($dom){
        $response = new Response($dom);
        if ($response->code()!='1000' && $response->code()!='1500'){
            $message = $response->message();
            if ($response->detailed_message()!=''){
                $message .= ": ".$response->detailed_message();
            }
            throw new Eurid_Exception($message, $response->code());
        }
    }

    function getServerTRID(){
        return $this->svTRID;
    }

    private static function printf_array($format, $arr)
    {
        return call_user_func_array('printf', array_merge((array)$format, $arr));
    }


}
?>
