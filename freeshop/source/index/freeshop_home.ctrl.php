<?php 
class freeshop_homeControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$userid=M("login")->userid;
		$follow=M("mod_freeshop_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($follow){
			$isFollow=1;
		}else{
			$isFollow=0;
		}
		$this->smarty->goAssign(array(
			"shopid"=>$shopid,
			"isFollow"=>$isFollow
		));
		$this->smarty->display("freeshop_home/index.html");
	}
	public function onToggleFollow(){
		M("login")->checkLogin();
		$shopid=get("shopid","i");
		$userid=M("login")->userid;
		$follow=M("mod_freeshop_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($follow){
			M("mod_freeshop_follow")->delete("userid=".$userid." AND shopid=".$shopid);
			M("mod_freeshop_feeds")->delete("userid=".$userid." AND shopid=".$shopid);
			$isFollow=0;
			M("mod_freeshop_shop")->changenum("follow_num",-1,"shopid=".$shopid);
		}else{
			M("mod_freeshop_follow")->insert(array(
				"userid"=>$userid,
				"shopid"=>$shopid,
				"dateline"=>time()
			));
			M("mod_freeshop_shop")->changenum("follow_num",1,"shopid=".$shopid);
			//同步数据
			$productids=M("mod_freeshop_product")->selectCols(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"fields"=>"productid"
			));
			if($productids){
				foreach($productids as $productid){
					M("mod_freeshop_feeds")->insert(array(
						"userid"=>$userid,
						"shopid"=>$shopid,
						"productid"=>$productid,
						"dateline"=>time()
					));
				}
			}
			$isFollow=1;
		}
		$this->goAll("success",0,array(
			"isFollow"=>$isFollow
		));
		 
	} 
	public function onApi(){
		$shopid=get("shopid","i");
		$shop=MM("freeshop","freeshop_shop")->get($shopid); 
		$start=get("per_page","i");
		$limit=6;
		$order=" productid DESC";
		$where=" shopid=".$shopid." AND status=1 ";
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("freeshop","freeshop_product")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"list"=>$list,
			"per_page"=>$per_page
		));
	}
	
	public function onShopFollow(){
		$userid=M("login")->userid;
		$shop=M("mod_freeshop_shop")->selectRow("userid=".$userid);
		if(empty($shop) || $shop["status"]!=1){
			$this->goAll("暂无权限",1);
		}
		$shopid=$shop["shopid"];
		$uids=M("mod_freeshop_follow")->selectCols(array(
			"where"=>" shopid=".$shopid,
			"fields"=>"userid"
		)); 
		$list=false;
		if(!empty($uids)){
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($uids as $userid){
				$list[]=$us[$userid];
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		 
		$this->smarty->display("freeshop_home/shopfollow.html");
	}
	
}