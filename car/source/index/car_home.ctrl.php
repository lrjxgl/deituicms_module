<?php 
class car_homeControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$userid=M("login")->userid;
		$follow=M("mod_car_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($follow){
			$isFollow=1;
		}else{
			$isFollow=0;
		}
		$this->smarty->goAssign(array(
			"shopid"=>$shopid,
			"isFollow"=>$isFollow
		));
		$this->smarty->display("car_home/index.html");
	}
	public function onToggleFollow(){
		M("login")->checkLogin();
		$shopid=get("shopid","i");
		$userid=M("login")->userid;
		$follow=M("mod_car_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($follow){
			M("mod_car_follow")->delete("userid=".$userid." AND shopid=".$shopid);
			M("mod_car_feeds")->delete("userid=".$userid." AND shopid=".$shopid);
			$isFollow=0;
			M("mod_car_shop")->changenum("follow_num",-1,"shopid=".$shopid);
		}else{
			M("mod_car_follow")->insert(array(
				"userid"=>$userid,
				"shopid"=>$shopid,
				"dateline"=>time()
			));
			M("mod_car_shop")->changenum("follow_num",1,"shopid=".$shopid);
			//同步数据
			$productids=M("mod_car_product")->selectCols(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"fields"=>"productid"
			));
			if($productids){
				foreach($productids as $productid){
					M("mod_car_feeds")->insert(array(
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
		$shop=MM("car","car_shop")->get($shopid); 
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
		$list=MM("car","car_product")->Dselect($ops,$rscount);
		$list2=MM("car","car_zuche")->Dselect($ops,$rscount);
		$glist=[];
		if($list){
			foreach($list as $v){
				$v["typeid"]=1;
				$glist[]=$v;
			}
		}
		if($list2){
			foreach($list2 as $v){
				$v["typeid"]=2;
				$glist[]=$v;
			}
		}
		if($glist){
			$glist=$this->array_orderby($glist,"dateline",SORT_DESC);
		}	
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			 
			"list"=>$glist,
			"per_page"=>$per_page
		));
	}
	function array_orderby()
	{
	    $args = func_get_args();
	    $data = array_shift($args);
	    foreach ($args as $n => $field) {
	        if (is_string($field)) {
	            $tmp = array();
	            foreach ($data as $key => $row)
	                $tmp[$key] = $row[$field];
	            $args[$n] = $tmp;
	            }
	    }
	    $args[] = &$data;
	    call_user_func_array('array_multisort', $args);
	    return array_pop($args);
	}
	public function onShopFollow(){
		$userid=M("login")->userid;
		$shop=M("mod_car_shop")->selectRow("userid=".$userid);
		if(empty($shop) || $shop["status"]!=1){
			$this->goAll("暂无权限",1);
		}
		$shopid=$shop["shopid"];
		$uids=M("mod_car_follow")->selectCols(array(
			"where"=>" shopid=".$shopid,
			"fields"=>"userid"
		)); 
		$list=false;
		if(!empty($uids)){
			$us=M("user")->getUserByIds($uids,"userid,nickname,description,user_head");
			foreach($uids as $userid){
				$list[]=$us[$userid];
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		 
		$this->smarty->display("car_home/shopfollow.html");
	}
	
}