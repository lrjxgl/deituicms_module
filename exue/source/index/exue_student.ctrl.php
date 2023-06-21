<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_studentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=exue_student&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" stid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("exue","exue_student")->Dselect($option,$rscount);
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
			$this->smarty->display("exue_student/index.html");
		}
		
		
		
		public function onShow(){
			$stid=get_post("stid","i");
			$data=MM("exue","exue_student")->get($stid,"stid,gender,truename,description,imgurl");
			
			$kcids=MM("exue","exue_order")->selectCols(array(
				"where"=>" stid=".$stid,
				"fields"=>"courseid"
			));
			if(!empty($kcids)){
				$kcList=MM("exue","exue_course")->Dselect(array(
					"where"=>" courseid in("._implode($kcids).") "
				));
			}
			
			$this->smarty->goassign(array(
				"data"=>$data,
				"kcList"=>$kcList
			));
			$this->smarty->display("exue_student/show.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=exue_student&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" stid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("exue","exue_student")->Dselect($option,$rscount);
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
			$this->smarty->display("exue_student/my.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$stid=get_post("stid","i");
			if($stid){
				$data=M("mod_exue_student")->selectRow(array("where"=>"stid=".$stid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("exue_student/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$stid=get_post("stid","i");
			$data=M("mod_exue_student")->postData();
			$data["status"]=1;
			if($stid){
				M("mod_exue_student")->update($data,"stid='$stid'");
			}else{
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				
				M("mod_exue_student")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$stid=get_post('stid',"i");
			$status=get_post("status","i");
			M("mod_exue_student")->update(array("status"=>$status),"stid=$stid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$stid=get_post('stid',"i");
			M("mod_exue_student")->update(array("status"=>11),"stid=$stid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>