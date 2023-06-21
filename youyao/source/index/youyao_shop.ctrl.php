<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class youyao_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=youyao_shop&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_youyao_shop")->select($option,$rscount);
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
			$this->smarty->display("youyao_shop/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=youyao_shop&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_youyao_shop")->select($option,$rscount);
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
			$this->smarty->display("youyao_shop/index.html");
		}
		
		public function onShow(){
			$shopid=get_post("shopid","i");
			$data=M("mod_youyao_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("youyao_shop/show.html");
		}
		public function onAdd(){
			$userid=M("login")->userid;
			$shop=MM("youyao","youyao_shop")->getShopByUserid($userid);
			if(empty($shop)){
				$this->goAll("暂无权限",1);
			}
			$site_city=M("site_city")->children(0); 
			$this->smarty->goassign(array(
				"shop"=>$shop,
				"site_city"=>$site_city
			));
			$this->smarty->display("youyao_shop/add.html");
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			$shop=MM("youyao","youyao_shop")->getShopByUserid($userid);
			if(empty($shop)){
				$this->goAll("暂无权限",1);
			}
			$shopid=$shop["shopid"];
			$data=M("mod_youyao_shop")->postData();
			if($shopid){
				M("mod_youyao_shop")->update($data,"shopid=".$shopid);
			}else{
				M("mod_youyao_shop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		
	}

?>