<?php
class abc{
	public function a(){
		echo "a";
	}
	public function b(){
		echo "b";
	}
}
$server = new SOAPServer(
     NULL,
     array(
      'uri' => 'https://fd175.skymvc.com/soap/server.php'
     )
   );
  
   $server->setClass('MyClass');
   $server->handle();