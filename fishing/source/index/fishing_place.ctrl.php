<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_placeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=fishing_place&a=default";
			$allow=get("allow","h");
			switch($allow){
				case "free":
					$where=" is_allow=0 ";
					break;
				case "warning":
					$where=" is_allow in(0,1)";
					break;
				 
			}
			$tag=get("tag","h");
			if(!empty($tag)){
				$where.=" AND tags like '%".$tag."%' ";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_place")->Dselect($option,$rscount);
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
			$this->smarty->display("fishing_place/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fishing_place&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_place")->select($option,$rscount);
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
			$this->smarty->display("fishing_place/index.html");
		}
		
		public function onMap(){
			$this->smarty->display("fishing_place/map.html");
		}
		public function onShow(){
			$placeid=get_post("placeid","i");
			$data=M("mod_fishing_place")->selectRow(array("where"=>"placeid=".$placeid));
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["tagsList"]=explode(" ",$data["tags"]);
			$admin=M("user")->getUser($data["userid"],"userid,nickname,user_head,description");
			$this->smarty->goassign(array(
				"data"=>$data,
				"admin"=>$admin
			));
			$this->smarty->display("fishing_place/show.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=fishing_place&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_place")->select($option,$rscount);
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
			$this->smarty->display("fishing_place/my.html");
		}
		
		
		public function onAdd(){
			$placeid=get_post("placeid","i");
			if($placeid){
				$data=M("mod_fishing_place")->selectRow(array("where"=>"placeid=".$placeid));
				$imgsdata=parseImgsData($data["imgsdata"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata
			));
			$this->smarty->display("fishing_place/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$placeid=get_post("placeid","i");
			$data=M("mod_fishing_place")->postData();
			$imgsdata=post("imgsdata","h");
			if(!empty($imgsdata)){
				$imgsdata=safeImgsData($imgsdata);
				$data["imgsdata"]=$imgsdata;
				$ex=explode(",",$imgsdata);
				$data["imgurl"]=$ex[0];
			}
			
			$data["userid"]=$userid;
			
			if($placeid){
				$row=M("mod_fishing_place")->where("placeid=?")->row($placeid);
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_fishing_place")->update($data,"placeid='$placeid'");
			}else{
				M("mod_fishing_place")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		 
		
		public function onDelete(){
			$placeid=get_post('placeid',"i");
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$row=M("mod_fishing_place")->selectRow("placeid=".$placeid);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			//假删除
			M("mod_fishing_place")->update(array("userid"=>1),"placeid=$placeid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>