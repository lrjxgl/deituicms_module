<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zblive_hoster_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zblive_hoster_apply&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" hostid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zblive_hoster_apply")->select($option,$rscount);
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
			$this->smarty->display("zblive_hoster_apply/index.html");
		}
		
		public function onPass(){
			$hostid=get_post('hostid',"i");
			
			M("mod_zblive_hoster_apply")->update(array("status"=>1),"hostid=$hostid");
			$row=M("mod_zblive_hoster_apply")->selectRow("hostid=$hostid");
			$hoster=M("mod_zblive_hoster")->selectRow("userid=".$row["userid"]);
			if(!$hoster){
				M("mod_zblive_hoster")->insert(array(
					"userid"=>$row["userid"],
					"status"=>1,
					"dateline"=>time()
				));
			}
			 
			$this->goall("审核通过");
		}
		
		public function onForbid(){
			$hostid=get_post('hostid',"i");
			M("mod_zblive_hoster_apply")->update(array("status"=>2),"hostid=$hostid");
			$this->goAll("禁止成功");
			 
		}
		
		
	}

?>