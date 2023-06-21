<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class flk_queue_logControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" 1 ";
			$url="/module.php?m=flk_queue_log&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_flk_queue_log")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$shopids[]=$v["shopid"];
					$uids[]=$v["userid"];
				}
				$sps=MM("flk","flk_shop")->getListByIds($shopids);
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["shopname"]=$sps[$v["shopid"]]["shopname"];
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["timeago"]=timeago($v["dateline"]);
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
			$this->smarty->display("flk_queue_log/index.html");
		}
		public function onShop(){
			$shopid=get("shopid","i");
			$shop=MM("flk","flk_shop")->get($shopid,"shopid,shopname,imgurl");
			$where=" shopid=".$shopid;
			$url="/module.php?m=flk_queue_log&a=shop&shopid=".$shopid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_flk_queue_log")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					 
					$uids[]=$v["userid"];
				}
				 
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					 
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["timeago"]=timeago($v["dateline"]);
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
					"shop"=>$shop
				)
			);
			$this->smarty->display("flk_queue_log/shop.html");
		}
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=flk_queue_log&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_flk_queue_log")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$shopids[]=$v["shopid"];
				}
				$sps=MM("flk","flk_shop")->getListByIds($shopids);
				foreach($data as $k=>$v){
					$v["shopname"]=$sps[$v["shopid"]]["shopname"];
					$v["timeago"]=timeago($v["dateline"]);
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
			$this->smarty->display("flk_queue_log/my.html");
		}
		
		
	}

?>