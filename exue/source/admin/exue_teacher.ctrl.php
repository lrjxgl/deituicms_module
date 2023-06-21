<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_teacherControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=exue_teacher&a=default";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND nickname like '%".$keyword."%' ";
				$url.="&keyword=".urlencode($keyword);
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tcid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_teacher")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$shopids[]=$v["shopid"];
				}
				$sps=MM("exue","exue_shop")->getListByIds($shopids);
				foreach($data as $k=>$v){
					$v["shop_title"]=$sps[$v["shopid"]]["title"];
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
					"keyword"=>$keyword
				)
			);
			$this->smarty->display("exue_teacher/index.html");
		}
		
		public function onAdd(){
			$tcid=get_post("tcid","i");
			if($tcid){
				$data=M("mod_exue_teacher")->selectRow(array("where"=>"tcid=".$tcid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("exue_teacher/add.html");
		}
		
		public function onSave(){
			$tcid=get_post("tcid","i");
			$data=M("mod_exue_teacher")->postData();
			if($tcid){
				M("mod_exue_teacher")->update($data,"tcid='$tcid'");
			}else{
				M("mod_exue_teacher")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$tcid=get_post('tcid',"i");
			$row=M("mod_exue_teacher")->selectRow("tcid=".$tcid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_exue_teacher")->update(array(
				"status"=>$status
			),"tcid=".$tcid);
			$this->goAll("success",0,$status);
		}
		public function onsite_recommend(){
			$tcid=get_post('tcid',"i");
			$row=M("mod_exue_teacher")->selectRow("tcid=".$tcid);
			$status=1;
			if($row["site_recommend"]==1){
				$status=0;
			}
			M("mod_exue_teacher")->update(array(
				"site_recommend"=>$status
			),"tcid=".$tcid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$tcid=get_post('tcid',"i");
			M("mod_exue_teacher")->update(array("status"=>11),"tcid=$tcid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>