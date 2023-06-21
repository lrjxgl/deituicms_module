<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_user_cardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="status in(0,1,2)";
			$url="/moduleadmin.php?m=gread_user_card&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_user_card")->select($option,$rscount);
			if($data){
				$uids=[];
				$shopids=[];
				foreach($data as $v){
					$uids[]=$v["userid"];
					$shopids[]=$v["shopid"];
				}
				$us=M("user")->getUserByIds($uids);
				$sps=MM("gread","gread_shop")->getListByIds($shopids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["shopname"]=$sps[$v["shopid"]]["title"];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("gread_user_card/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_gread_user_card")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_user_card/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_gread_user_card")->postData();
			if($id){
				M("mod_gread_user_card")->update($data,"id='$id'");
			}else{
				M("mod_gread_user_card")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_gread_user_card")->update(array("bstatus"=>$bstatus),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_gread_user_card")->update(array("bstatus"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>