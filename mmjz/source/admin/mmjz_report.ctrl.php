<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class mmjz_reportControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "doing":
					$where=" status=1 ";
					break;
				case "finish":
					$where=" status=2 ";
					break;
				case "all":
					$where=" status in(0,1,2) ";
					break;
				default:
					$where=" status=0 ";
					break;
			}
			 
			$url="/moduleadmin.php?m=mmjz_report&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mmjz_report")->select($option,$rscount);
			if($data){
				$shopids=[];
				foreach($data as $v){
					$shopids[]=$v["shopid"];
				}
				$sps=MM("mmjz","mmjz_shop")->getListByIds($shopids);
				
				foreach($data as $k=>$v){
					if(!empty($v["imgsdata"])){
						$imgs=explode(",",$v["imgsdata"]);
						$ims=array();
						foreach($imgs as $im){
							$ims[]=images_site($im);
						}
						$v["imglist"]=$ims;
					}
					$v["shopname"]=$sps[$v["shopid"]]["shopname"];
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
			$this->smarty->display("mmjz_report/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_mmjz_report")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_mmjz_report")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>