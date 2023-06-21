<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class household_senderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=household_sender&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" senderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_household_sender")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
			$this->smarty->display("household_sender/index.html");
		}
		
		public function onAdd(){
			$senderid=get_post("senderid","i");
			if($senderid){
				$data=M("mod_household_sender")->selectRow(array("where"=>"senderid=".$senderid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("household_sender/add.html");
		}
		
		public function onSave(){
			$senderid=get_post("senderid","i");
			$data=M("mod_household_sender")->postData();
			if($senderid){
				M("mod_household_sender")->update($data,"senderid='$senderid'");
			}else{
				M("mod_household_sender")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$senderid=get_post('senderid',"i");
			$status=get_post("status","i");
			M("mod_household_sender")->update(array("status"=>$status),"senderid=$senderid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$senderid=get_post('senderid',"i");
			M("mod_household_sender")->update(array("status"=>11),"senderid=$senderid");
			$this->goAll("删除成功");
			 
		}
		
		
		
		
	}

?>