<?php
class printerControl extends skymvc{
	
	public $shoptable;
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		 
	}
	
	public function onDefault(){
		$data=MM("printer","mod_printer")->select(array(
			"where"=>"tablename='".SHOPTABLE."' AND shopid=".SHOPID
		));
		$this->smarty->assign(array(
			"data"=>$data
		));
		$this->smarty->display("printer/index.html");
	}
	
	public function onAdd(){
		$id=get('id','i');
		if($id){
			$row=MM("printer","mod_printer")->selectRow("id=".$id);
			if($row['tablename']!=SHOPTABLE or $row['shopid']!=SHOPID){
				$this->goALl("暂无权限");
			}
		}
		$this->smarty->assign(array(
			"data"=>$row
		));
		$this->smarty->display("printer/add.html");
	}
	
	public function onSave(){
		$data=M("mod_printer")->postData();
		$data['tablename']=SHOPTABLE;
		$data['shopid']=SHOPID;
		$data['dateline']=time();
		$data['bstatus']=2;
		$id=get_post('id','i');
		if($id){
			$row=M("mod_printer")->selectRow("id=".$id);
			if($row['tablename']!=SHOPTABLE or $row['shopid']!=SHOPID){
				$this->goALl("暂无权限");
			}
			M("mod_printer")->update($data,"id=".$id);
		}else{
			M("mod_printer")->insert($data);
		}
		$this->goAll("success");
	}
	
	public function onDelete(){
		$id=get('id','i');
		$row=M("mod_printer")->selectRow("id=".$id);
		if($row['tablename']!=SHOPTABLE or $row['shopid']!=SHOPID){
			$this->goALl("暂无权限");
		}
		M("mod_printer")->delete("id=".$id);
		$this->goALl("删除成功");
	}
	
}

?>