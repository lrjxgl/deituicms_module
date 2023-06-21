<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_pp_ptsControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "online":
					$where=" status=1 ";
					break;
				case "offline":
					$where=" status=2 ";
					break;
				case "all":
					$where=" status in(0,1,2)";
					break;
				default:
					$where=" status=0 ";
					break;
			}
			$url="/moduleadmin.php?m=zbtao_pp_pts&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ptid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zbtao_pp_pts")->select($option,$rscount);
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
			$this->smarty->display("zbtao_pp_pts/index.html");
		}
		
		public function onStatus(){
			$ptid=get_post('ptid',"i");
			 
			$row=M("mod_zbtao_pp_pts")->selectRow("ptid=".$ptid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_zbtao_pp_pts")->update(array("status"=>$status),"ptid=$ptid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$ptid=get_post('ptid',"i");
			M("mod_zbtao_pp_pts")->update(array("status"=>11),"ptid=$ptid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>