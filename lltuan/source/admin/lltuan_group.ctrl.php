<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class lltuan_groupControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=lltuan_group&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_lltuan_group")->select($option,$rscount);
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
			$this->smarty->display("lltuan_group/index.html");
		}
		
		public function onAdd(){
			$gid=get_post("gid","i");
			if($gid){
				$data=M("mod_lltuan_group")->selectRow(array("where"=>"gid=".$gid));				
			}
			$placeList=MM("lltuan","lltuan_place")->select(array(
				"where"=>" status=1 "
			));
			$proList=MM("lltuan","lltuan_product")->select(array(
				"where"=>" status=1 "
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"placeList"=>$placeList,
				"proList"=>$proList
			));
			$this->smarty->display("lltuan_group/add.html");
		}
		
		public function onSave(){
			$gid=get_post("gid","i");
			$content=post("content","x");
			$data=M("mod_lltuan_group")->postData();
			$data["content"]=$content;
			if($gid){
				M("mod_lltuan_group")->update($data,"gid=".$gid);
			}else{
				M("mod_lltuan_group")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$gid=get_post('gid',"i");
			$row=M("mod_lltuan_group")->selectRow("gid=".$gid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_lltuan_group")->update(array("status"=>$status),"gid=".$gid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$gid=get_post('gid',"i");
			M("mod_lltuan_group")->update(array("status"=>11),"gid=".$gid);
			$this->goAll("删除成功");
			 
		}
		
		
		
	}

?>