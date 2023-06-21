<?php
class printer_logControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		
	}
	
	public function onDefault(){
		
		$rscount=true;
		$where="   shopid=".SHOPID." AND tablename='".SHOPTABLE."' ";
		$url="/moduleshop.php?m=printer_log&shoptable=".SHOPTABLE;
		
		$start=get_post('per_page','i');
		$limit=20;
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"last_time DESC"
		);
		$data=M("mod_printer_log")->select($option,$rscount);
		 
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->assign(array(
			"data"=>$data,
			"rscount"=>$rscount,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("printer_log/index.html");
	}
	
}

?>