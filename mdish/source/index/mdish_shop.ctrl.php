<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class mdish_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="module.php?m=mdish_shop&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_shop")->select($option,$rscount);
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
			$this->smarty->display("mdish_shop/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="module.php?m=mdish_shop&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_shop")->select($option,$rscount);
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
			$this->smarty->display("mdish_shop/index.html");
		}
		
		public function onShow(){
			$shopid=get_post("shopid","i");
			$data=M("mod_mdish_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("mdish_shop/show.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="module.php?m=mdish_shop&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_shop")->select($option,$rscount);
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
			$this->smarty->display("mdish_shop/my.html");
		}
		
		public function onAdd(){
			$shopid=get_post("shopid","i");
			
			$this->smarty->goassign(array(
				"a"=>1
			));
			$this->smarty->display("mdish_shop/add.html");
		}
		
		public function onSave(){
			$shopid=get_post("shopid","i");
			$data=M("mod_mdish_shop")->postData();
			$data["userid"]=M("login")->userid;
			$spnum=M("mod_mdish_shop")->selectOne(array(
				"where"=>" userid=".$data["userid"]." AND status in(0,1) ",
				"fields"=>"count(*) as ct"
			));
			if($spnum>3){
				$this->goAll("一个用户只能添加3个店铺",1);
			}
			$data["dateline"]=time();
			M("mod_mdish_shop")->insert($data);
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$status=get_post("status","i");
			M("mod_mdish_shop")->update(array("status"=>$status),"shopid=$shopid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_mdish_shop")->update(array("status"=>11),"shopid=$shopid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>