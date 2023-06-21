<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gold_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=gold_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gold_product")->select($option,$rscount);
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
			$this->smarty->display("gold_product/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_gold_product")->selectRow(array("where"=>"id=".$id));
			if($data["status"]!=1){
				$this->goAll("商品已下架",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			$userid=M("login")->userid;
			$addr=M("user_lastaddr")->get($userid);
			$user=M("user")->where("userid=".$userid)->field("userid,gold")->row();
			$this->smarty->goassign(array(
				"data"=>$data,
				"addr"=>$addr,
				"user"=>$user
			));
			$this->smarty->display("gold_product/show.html");
		}
		
	}

?>