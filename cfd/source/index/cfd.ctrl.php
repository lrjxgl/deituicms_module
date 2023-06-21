<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cfdControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onInit(){
			if(!in_array(get('a'),array("default",'list','show'))){
				M("login")->checkLogin();
			}
		}
		public function onDefault(){
			
			$type=get("type","h");
			$url="/module.php?m=cfd&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			switch($type){
				case "doing":
					$where=" status=1 AND isfinish=0 ";
					break;
				case "finish":
					$where=" status=1 AND isfinish=1 ";
					break;
				default:
					$where=" status=1 ";
					$type="all";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" cfdid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cfd")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$v['videourl']=images_site($v['videourl']);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
					"type"=>$type
				)
			);
			$this->smarty->display("cfd/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=cfd&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" cfdid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cfd")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$v['videourl']=images_site($v['videourl']);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("cfd/list.html");
		}
		
		public function onShow(){
			$cfdid=get_post("cfdid","i");
			$data=M("mod_cfd")->selectRow(array("where"=>"cfdid={$cfdid}"));
			if(empty($data) || $data["status"]!=1){
				$this->goALl("该众筹已下架",1);
			}
			$data['imgurl']=images_site($data['imgurl']);
			$data['videourl']=images_site($data['videourl']);
			$rewardList=M("mod_cfd_reward")->select(array(
				"where"=>" cfdid=".$cfdid." AND status=1 ",
				"order"=>"money ASC"
			));
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"rewardList"=>$rewardList
			));
			$this->smarty->display("cfd/show.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/module.php?m=cfd&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" cfdid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cfd")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$v['videourl']=images_site($v['videourl']);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("cfd/my.html");
		}
		
		public function onAdd(){
			$cfdid=get_post("cfdid","i");
			$userid=M("login")->userid;
			if($cfdid){
				$data=M("mod_cfd")->selectRow(array("where"=>"cfdid={$cfdid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("cfd/add.html");
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			$cfdid=get_post("cfdid","i");

			$data=M("mod_cfd")->postData();
			$data['endtime']=date("Y-m-d H:i:s",$data['endtime']);
			if($cfdid){
				$row=M("mod_cfd")->selectRow("cfdid=".$cfdid);
				if($row['userid']!=$userid){
					$this->goAll("暂无权限");
				}
				M("mod_cfd")->update($data,"cfdid='$cfdid'");
			}else{
				$data['userid']=$userid;
				M("mod_cfd")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$cfdid=get_post('cfdid',"i");
			$status=get_post("status","i");
			$userid=M("login")->userid;
			$row=M("mod_cfd")->selectRow("cfdid=".$cfdid);
			if($row['userid']!=$userid){
				$this->goAll("暂无权限");
			}
			M("mod_cfd")->update(array("status"=>$status),"cfdid=$cfdid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$cfdid=get_post('cfdid',"i");
			$userid=M("login")->userid;
			$row=M("mod_cfd")->selectRow("cfdid=".$cfdid);
			if($row['userid']!=$userid){
				$this->goAll("暂无权限");
			}
			M("mod_cfd")->update(array("status"=>11),"cfdid=$cfdid");
			$this->goAll("删除成功");
			 
		}
		
		public function onUser(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$user=M("login")->getUser();
			 
			$this->smarty->goAssign(array(
				"user"=>$user
			));
			$this->smarty->display("cfd/user.html");
		} 
		
		
	}

?>