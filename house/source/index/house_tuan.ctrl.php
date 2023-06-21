<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_tuanControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=house_tuan&a=default";
			$type=get("type","h");
			 
			$url.="&type=".$type;
			$time=date("Y-m-d H:i:s");
			switch($type){
				case "begin":
					$where.=" AND stime>'".$time."' ";
					break;
				case "doing":
					$where.=" AND stime<'".$time."' AND etime>'".$time."' ";
					break;
				case "finish":
					$where.=" AND etime<'".$time."' ";
					break;
				default:
					
					break;
			}
			 
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=MM("house","house_tuan")->Dselect($option,$rscount);
			 
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("house_tuan/index.html");
		}
		 
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=house_tuan&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_house_tuan")->select($option,$rscount);
			if($list){
				foreach($list as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$list[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("house_tuan/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_house_tuan")->selectRow(array("where"=>"id=".$id." AND status=1 "));
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["wxewm"]=images_site($data["wxewm"]);
			$userid=M("login")->userid;
			$shareurl=HTTP_HOST."/module.php?m=house_tuan&a=show&id=".$id."&invite_hsuserid=".$userid;
			//邀请推广
			$invite_hsuserid=get("invite_hsuserid","i");
			if($invite_hsuserid){
				$_SESSION["ss_invite_hsuserid"]=$invite_hsuserid;
				setcookie("ck_invite_hsuserid",$invite_hsuserid,time()+3600*24,"/",DOMAIN);
			}elseif(isset($_COOKIE["ck_invite_hsuserid"])){
				$_SESSION["ss_invite_hsuserid"]=intval($_COOKIE["ck_invite_hsuserid"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"shareurl"=>$shareurl,
				"invite_hsuserid"=>$invite_hsuserid
			));
			$this->smarty->display("house_tuan/show.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=house_tuan&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_house_tuan")->select($option,$rscount);
			if($list){
				foreach($list as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$list[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("house_tuan/my.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$agent=M("mod_house_agent")->selectRow(array(
				'where'=>"userid=".$userid,
				"fields"=>"userid,status"
			));
			if(!$agent){
				$this->goAll("只有经纪人才能申请看房团",1);
			}
			$id=get_post("id","i");
			if($id){
				
				$data=M("mod_house_tuan")->selectRow(array("where"=>"id=".$id));
				
				if($data["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				$data["true_imgurl"]=images_site($data["imgurl"]); 
				$data["true_wxewm"]=images_site($data["wxewm"]); 
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("house_tuan/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			$data=M("mod_house_tuan")->postData();
			$userid=M("login")->userid;
			$agent=M("mod_house_agent")->selectRow(array(
				'where'=>"userid=".$userid,
				"fields"=>"userid,status"
			));
			if(!$agent){
				$this->goAll("只有经纪人才能申请看房团",1);
			}
			if(empty($data["title"])){
				$this->goAll("请输入名称",1);
			}
			if(empty($data["imgurl"])){
				$this->goAll("请上传图片",1);
			}
			if(empty($data["description"])){
				$this->goAll("请输入简介",1);
			}
			if($id){
				$row=M("mod_house_tuan")->selectRow("id=".$id);
				if($row["status"]==1){
					$this->goAll("已经审核通过无法更改",1);
				}
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				$data["status"]=0;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_house_tuan")->update($data,"id=".$id);
			}else{
				$data["userid"]=$userid;
				$data["status"]=0;
				M("mod_house_tuan")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			M("login")->checkLogin();
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_house_tuan")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$status=get_post("status","i");
			M("mod_house_tuan")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			M("login")->checkLogin();
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_house_tuan")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_house_tuan")->update(array("status"=>11),"id=".$id);
			$this->goAll("删除成功");
			 
		}
		
	}

?>