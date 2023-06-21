<?php
class tutor_shopControl extends skymvc{
	
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=MM("tutor","tutor_shop")->get($shopid);
		$sp=M("mod_tutor_shop_data")->selectRow("shopid=".$shopid);
		$shop["content"]=$sp["content"];
		$list=MM("tutor","tutor_lesson")->Dselect(array(
			"where"=>" status=1" 
		));
		$certList=MM("tutor","tutor_shop_cert")->Dselect(array(
			"where"=>" status=1 AND shopid=".$shopid
		));
		$isFollow=0;
		$userid=M("login")->userid;
		if($userid){
			$f=M("mod_tutor_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
			if($f){
				$isFollow=1;
			}
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"isFollow"=>$isFollow,
			"shopid"=>$shopid,
			"list"=>$list,
			"certList"=>$certList
		));
		$this->smarty->display("tutor_shop/index.html");
	}
	
	
	public function onRaty(){
		$shopid=get("shopid","i");
		$start=get("per_page","i");
		$limit=12;
		$where=" shopid=".$shopid;
		$order=" id DESC";
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit
		);
		$rscount=true;
		$list=M("mod_tutor_order_raty")->select($ops,$rscount);
		if($list){
			$uids=[];
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["time"]=date("Y-m-d",$v["dateline"]);
				$list[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"rscount"=>$rscount
		));
		
	}
	
	public function onToggleFollow(){
		M("login")->checkLogin();
		$shopid=get("shopid","i");
		$userid=M("login")->userid;
		$follow=M("mod_tutor_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($follow){
			M("mod_tutor_follow")->delete("userid=".$userid." AND shopid=".$shopid);
			M("mod_tutor_feeds")->delete("userid=".$userid." AND shopid=".$shopid);
			$isFollow=0;
			M("mod_tutor_shop")->changenum("follow_num",-1,"shopid=".$shopid);
		}else{
			M("mod_tutor_follow")->insert(array(
				"userid"=>$userid,
				"shopid"=>$shopid,
				"dateline"=>time()
			));
			M("mod_tutor_shop")->changenum("follow_num",1,"shopid=".$shopid);
			//同步数据
			$productids=M("mod_tutor_lesson")->selectCols(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"fields"=>"lessonid"
			));
			if($productids){
				foreach($productids as $lessonid){
					M("mod_tutor_feeds")->insert(array(
						"userid"=>$userid,
						"shopid"=>$shopid,
						"lessonid"=>$lessonid,
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
	
}