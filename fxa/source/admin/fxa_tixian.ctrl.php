<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fxa_tixianControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fxa_tixian&a=default";
			$type=get("type","h");
			switch($type){
				case "all":
					break;
				case "finish":
					$where=" status=1 ";
					break;
				case "cancel":
					$where=" status=4 ";
					break;
				default:
					$where=" status=0 ";
					break;
			}
			$url.="&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fxa_tixian")->select($option,$rscount);
			if($data){
				$statusList=array(
					0=>"待处理",
					1=>"已完成",
					4=>"已取消"
				);
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["status_name"]=$statusList[$v["status"]];
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
					"url"=>$url,
					"type"=>$type
				)
			);
			$this->smarty->display("fxa_tixian/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_fxa_tixian")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_fxa_tixian")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onFinish(){
			$id=get_post('id',"i");
			M("mod_fxa_tixian")->update(array("status"=>1),"id=$id");
			$this->goAll("打款成功");
		}
		public function onCancel(){
			$id=get_post('id',"i");
			M("mod_fxa_tixian")->update(array("status"=>4),"id=$id");
			$this->goAll("取消打款成功");
		}
		
	}

?>