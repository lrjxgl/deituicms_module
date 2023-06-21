<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class job_jianzhi_baomingControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=job_jianzhi_baoming&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_job_jianzhi_baoming")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$ids[]=$v["objectid"];
				}
				$jzs=MM("job","job_jianzhi")->getListByIds($ids);
				foreach($data as $k=>$v){
					$v=$jzs[$v["objectid"]];
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
			$this->smarty->display("job_jianzhi_baoming/index.html");
		}
		
		public function onJianZhi(){
			$objectid=get("objectid","i");
			$where=" objectid=".$objectid." AND status in(0,1,2)";
			$url="/module.php?m=job_jianzhi_baoming&a=jianzhi";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_job_jianzhi_baoming")->select($option,$rscount);
			$jianzhi=M("mod_job_jianzhi")->selectRow("id=".$objectid);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"jianzhi"=>$jianzhi,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			); 
			$this->smarty->display("job_jianzhi_baoming/jianzhi.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_job_jianzhi_baoming")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("job_jianzhi_baoming/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			
			$id=get_post("id","i");
			$data=M("mod_job_jianzhi_baoming")->postData();
			$userid=M("login")->userid;
			$data["userid"]=$userid;
			$row=M("mod_job_jianzhi_baoming")->selectRow("objectid=".$data["objectid"]." AND userid=".$userid);
			if($row){
				$this->goAll("你已经报名过了",1);
			}
			$telephone=post("telephone","h");
			if(empty($telephone)){
				$this->goAll("请输入手机号码",1);
			}
			$user=M("user")->selectRow("userid=".$userid);
			if($user["telephone"]=="" && $telephone){
				$tUser=M("user")->selectRow("telephone='.$telephone.'");
				if(!$tUser){
					M("user")->update(array(
						"telephone"=>$telephone
					),"userid=".$userid);
				}
			}
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_job_jianzhi_baoming")->insert($data);
			 
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_job_jianzhi_baoming")->update(array("bstatus"=>$bstatus),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_job_jianzhi_baoming")->update(array("bstatus"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>