<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class mdish_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1)";
			$url="module.php?m=mdish_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			$order=" productid DESC";
			switch($type){
				case "recommend":
					$where.=" AND isrecommend=1 AND love_num>=1 ";
					break;
				case "hot":
					$where.=" AND ishot=1 ";
					$order="love_num DESC";
					break;
				case "new":
					
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_product")->select($option,$rscount);
			if($data){
				$spids=array();
				foreach($data as  $v){
					$spids[]=$v["shopid"];
				}
				$shops=MM("mdish","mdish_shop")->getListByIds($spids,"shopid,title");
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$v["shop"]=$shops[$v["shopid"]];
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
			$this->smarty->display("mdish_product/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="module.php?m=mdish_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_product")->select($option,$rscount);
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
			$this->smarty->display("mdish_product/index.html");
		}
		
		public function onShow(){
			$userid=M("login")->userid;
			$productid=get_post("productid","i");
			$data=M("mod_mdish_product")->selectRow(array("where"=>"productid=".$productid));
			if($data["status"]!=1){
				$this->goAll("还未审核通过",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["mp4url"]=images_site($data["mp4url"]);
			if($userid){
				$view=M("mod_mdish_view")->selectRow("userid=".$userid." AND productid=".$productid);
				if(!$view){
					M("mod_mdish_view")->insert(array(
						"userid"=>$userid,
						"productid"=>$productid,
						"shopid"=>$data["shopid"],
						"dateline"=>time()
					));
					M("mod_mdish_product")->update(array(
						"view_num"=>$data["view_num"]+1
					),"productid=".$productid);
				}
			}
			$shop=M("mod_mdish_shop")->selectRow("shopid=".$data["shopid"]);
			$seo=array(
				"title"=>$data["title"],
				"description"=>$data["description"]
			);
			$this->smarty->goassign(array(
				"seo"=>$seo,
				"data"=>$data,
				"shop"=>$shop
			));
			$this->smarty->display("mdish_product/show.html");
		}
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="module.php?m=mdish_product&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_product")->select($option,$rscount);
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
			$this->smarty->display("mdish_product/my.html");
		}
		public function onAdd(){
			$userid=M("login")->userid;
			$productid=get_post("productid","i");
			$shoplist=M("mod_mdish_shop")->select(array(
				"where"=>" userid=".$userid." AND status in(0,1)",
				"fields"=>"shopid,title"
			));
			if(empty($shoplist)){
				$this->goAll("请先添加商家",1);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"shoplist"=>$shoplist
			));
			$this->smarty->display("mdish_product/add.html");
		}
		
		public function onSave(){
			$productid=get_post("productid","i");
			$un=array("view_num","love_num","status");
			$data=M("mod_mdish_product")->postData($un);
			$spnum=M("mod_mdish_product")->selectOne(array(
				"where"=>" shopid=".$data["shopid"]." AND status in(0,1) ",
				"fields"=>"count(*) as ct"
			));
			if($spnum>3){
				$this->goAll("一个商家只能上传3个主打菜",1);
			}
			$data["userid"]=M("login")->userid;
			$data["dateline"]=time();
			M("mod_mdish_product")->insert($data);
			$this->goall("保存成功");
		}
		
		 
		
		public function onDelete(){
			$productid=get_post('productid',"i");
			M("mod_mdish_product")->update(array("status"=>11),"productid=$productid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>