<?php
class printer_tplControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		
	}
	
	public function onDefault(){
		$row=M("mod_printer_tpl")->selectRow("tablename='".SHOPTABLE."' AND shopid=".SHOPID);
		if(!$row){
			M("mod_printer_tpl")->insert(array(
				"tablename"=>SHOPTABLE,
				"shopid"=>SHOPID,
				"dateline"=>time()
				
			));
			$row=M("mod_printer_tpl")->selectRow("tablename='".SHOPTABLE."' AND shopid=".SHOPID);
		}
		$this->smarty->assign(array(
			"data"=>$row
		));
		$this->smarty->display("printer_tpl/index.html");
	}
	
	public function onSave(){
		$data['shead']=post('shead','x');
		$data['sfoot']=post('sfoot','x');
		$data['sqr']=post('sqr','x');
		$data['num']=max(1,post('num','x'));
		M("mod_printer_tpl")->update($data,"tablename='".SHOPTABLE."' AND shopid=".SHOPID);
		$this->goAll("success");
	}
	
}

?>