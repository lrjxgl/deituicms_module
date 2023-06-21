<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class paotui_addrControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=paotui_addr&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" addrid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_paotui_addr")->select($option,$rscount);
			if($data){
				$uids=array();
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$data[$k]=$v;
					
				}
				
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("paotui_addr/index.html");
		}
		
		public function onStatus(){
			$addrid=get_post('addrid',"i");
			$status=get_post("status","i");
			M("mod_paotui_addr")->update(array("status"=>$status),"addrid=$addrid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$addrid=get_post('addrid',"i");
			M("mod_paotui_addr")->update(array("status"=>11),"addrid=$addrid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>