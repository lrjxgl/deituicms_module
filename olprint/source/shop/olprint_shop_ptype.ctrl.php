<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class olprint_shop_ptypeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$ptypeList=MM("olprint","olprint_ptype")->ptypeList();
			$slist=M("mod_olprint_shop_ptype")->selectCols(array(
				"where"=>" shopid=".SHOPID,
				"fields"=>"ptype"
			));
			if(empty($slist)){
				foreach($ptypeList as $v){
					$v["shopid"]=SHOPID;
					 
					M("mod_olprint_shop_ptype")->insert($v);
				}
			}else{
				foreach($ptypeList as $v){
					if(!in_array($v["ptype"],$slist)){
						$v["shopid"]=SHOPID;
						M("mod_olprint_shop_ptype")->insert($v);
					}
				}
			}
			$list=M("mod_olprint_shop_ptype")->select(array(
				"where"=>" shopid=".SHOPID,
				 
			));
			$this->smarty->goassign(
				array(
					"list"=>$list,
					 
				)
			);
			$this->smarty->display("olprint_shop_ptype/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_olprint_shop_ptype")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("olprint_shop_ptype/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_olprint_shop_ptype")->postData();
			if($id){
				$row=M("mod_olprint_shop_ptype")->selectRow("id=".$id);
				if($row["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				M("mod_olprint_shop_ptype")->update($data,"id='$id'");
			}else{
				M("mod_olprint_shop_ptype")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_olprint_shop_ptype")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]==1){
				$status=0;
			}else{
				$status=1;
			}
			
			M("mod_olprint_shop_ptype")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		 
		
	}

?>