<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class shanxin_joinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="module.php?m=shanxin_join&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shanxin_join")->select($option,$rscount);
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
			$this->smarty->display("shanxin_join/index.html");
		}
		
		public function onList(){
			$sid=get_post("sid","i");
			$shanxin=M("mod_shanxin")->selectRow(array("where"=>"sid=".$sid));
			$where=" sid=".$sid." AND status in(0,1,2)";
			$url="module.php?m=shanxin_join&a=list&sid=".$sid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shanxin_join")->select($option,$rscount);
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
					"url"=>$url,
					"shanxin"=>$shanxin
				)
			);
			$this->smarty->display("shanxin_join/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_shanxin_join")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("shanxin_join/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			$sid=get_post("sid","i");
			$userid=M("login")->userid;
			$shanxin=M("mod_shanxin")->selectRow(array("where"=>"sid=".$sid));
			$data=M("mod_shanxin_join")->selectRow(array("where"=>"userid=".$userid." AND sid=".$sid));
			$this->smarty->goassign(array(
				"data"=>$data,
				"shanxin"=>$shanxin
			));
			$this->smarty->display("shanxin_join/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_shanxin_join")->postData();
			if(empty($data["imgurl"])){
				$this->goAll("请选择照片",1);
			}
			if(empty($data["nickname"])){
				$this->goAll("请填写名字",1);
			}
			if(empty($data["telephone"]) || !is_tel($data["telephone"])){
				$this->goAll("请正确填写电话",1);
			}
			if(empty($data["address"])){
				$this->goAll("请填写收货地址",1);
			}
			$userid=M("login")->userid;
			$row=M("mod_shanxin_join")->selectRow("sid=".$data["sid"]." AND userid=".$userid);
			if($row){
				$this->goAll("你已经参与了",1);
			}
			$data["userid"]=$userid;
			$data["createtime"]=time();
			M("mod_shanxin_join")->insert($data);
			M("mod_shanxin")->changenum("join_num",1,"sid=".$data["sid"]);
			$this->goall("保存成功");
		}
		
		
	}

?>