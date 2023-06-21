<?php
class printerModel extends model{
	public $table="mod_printer";
	public function pcomList(){
		return array(
			1=>"飞鹅打印机",
			2=>"易联云打印机"
		);
	}
}

?>