<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_free_placeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fishing_free_place&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_free_place")->Dselect($option,$rscount);
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
			$this->smarty->display("fishing_free_place/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fishing_free_place&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_free_place")->Dselect($option,$rscount);
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
			$this->smarty->display("fishing_free_place/index.html");
		}
		
		public function onShow(){
			$placeid=get_post("placeid","i");
			$data=M("mod_fishing_free_place")->selectRow(array("where"=>"placeid=".$placeid));
			$data["imgurl"]=images_site($data["imgurl"]);
			//管理员
			$admin=[];
			if($data["userid"]){
				$admin=M("mod_fishing_free_admin")->selectRow("placeid=".$placeid." AND status=1 AND userid=".$data["userid"]);
				$admin["user_head"]=images_site($admin["user_head"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"admin"=>$admin
			));
			$this->smarty->display("fishing_free_place/show.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/module.php?m=fishing_free_place&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_free_place")->Dselect($option,$rscount);
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
			$this->smarty->display("fishing_free_place/my.html");
		}
		
		
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$placeid=get_post("placeid","i");
			if($placeid){
				$data=M("mod_fishing_free_place")->selectRow(array("where"=>"placeid=".$placeid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
				if($data["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fishing_free_place/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$placeid=get_post("placeid","i");
			
			$data=M("mod_fishing_free_place")->postData();
			if($placeid){
				$row=M("mod_fishing_free_place")->selectRow(array("where"=>"placeid=".$placeid));
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_fishing_free_place")->update($data,"placeid=".$placeid);
			}else{
				M("mod_fishing_free_place")->insert($data);
			}
			$this->goall("保存成功");
		}
		
	}

?>