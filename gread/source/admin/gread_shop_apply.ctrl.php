<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_shop_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="";
			$url="/module.php?m=gread_shop_apply&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_shop_apply")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("gread_shop_apply/index.html");
		}
		
		public function onAdd(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$data=M("mod_gread_shop_apply")->selectRow(array("where"=>"shopid={$shopid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_shop_apply/add.html");
		}
		
		public function onSave(){
			$shopid=get_post("shopid","i");
			$data=M("mod_gread_shop_apply")->postData();
			if($shopid){
				M("mod_gread_shop_apply")->update($data,"shopid='$shopid'");
			}else{
				M("mod_gread_shop_apply")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_gread_shop_apply")->update(array("bstatus"=>$bstatus),"shopid=$shopid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_gread_shop_apply")->update(array("bstatus"=>11),"shopid=$shopid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>