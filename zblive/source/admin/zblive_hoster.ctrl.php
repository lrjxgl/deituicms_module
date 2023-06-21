<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zblive_hosterControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zblive_hoster&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" hostid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zblive_hoster")->select($option,$rscount);
			if(!empty($data)){
				$uids=[];
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
			$this->smarty->display("zblive_hoster/index.html");
		}
		
		public function onStatus(){
			$hostid=get_post('hostid',"i");
			$status=get_post("status","i");
			M("mod_zblive_hoster")->update(array("status"=>$status),"hostid=$hostid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$hostid=get_post('hostid',"i");
			M("mod_zblive_hoster")->update(array("status"=>11),"hostid=$hostid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>