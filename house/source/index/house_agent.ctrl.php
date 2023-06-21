<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_agentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=house_agent&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where,
				"fields"=>"uhead,truename,description,id"
			);
			$rscount=true;
			$data=MM("house","house_agent")->Dselect($option,$rscount);
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
			$this->smarty->display("house_agent/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=house_agent&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("house","house_agent")->Dselect($option,$rscount);
			 
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
			$this->smarty->display("house_agent/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_house_agent")->selectRow(array("where"=>"id=".$id));
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$data["uhead"]=images_site($data["uhead"]);
			$user=M("user")->getUser($data["userid"],"userid,user_head,nickname,follow_num,followed_num,description");
			//
			$list=MM("house","house_resource")->Dselect(array(
				"where"=>" userid=".$user["userid"],
				"limit"=>24
			));;
			$this->smarty->goassign(array(
				"data"=>$data,
				"user"=>$user,
				"list"=>$list
			));
			$this->smarty->display("house_agent/show.html");
		}
		
	}

?>