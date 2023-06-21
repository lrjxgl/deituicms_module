<?php
class gread_guestControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$this->onIndex();
	}
	
	public function onIndex(){
		$start=get("per_page","i");
		$limit=24; 
		$rscount=true;
		$userid=M("login")->userid;
		$list=M("mod_gread_guestindex")->select(array(
			"where"=>" ukey='user' AND userid=".$userid,
			"order"=>" createtime DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if($list){
			foreach($list as $v){
				$shopids[]=$v["shopid"];
			}
			$sps=MM("gread","gread_shop")->getListByIds($shopids,"shopid,shopname,imgurl");
			
			foreach($list as $k=>$v){
				
				$v["shopname"]=$sps[$v["shopid"]]["shopname"];
				$v["shop_imgurl"]=$sps[$v["shopid"]]["imgurl"];
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$list[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
		));
		$this->smarty->display("gread_guest/index.html");
	}
	
	public function onUser(){
		$id=get("id","i");
		$shopid=MM("gread","gread")->getShopid();
		$shop=MM("gread","gread_shop")->get($shopid,"shopid,title,imgurl");
		$userid=M("login")->userid;
		$shopid=get("shopid","i");
		$user=M("user")->getUser($userid);
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"shopid"=>$shopid,
			"user"=>$user
		));
		$this->smarty->display("gread_guest/user.html");
	}
	//根据分类获取对话 
	 
	public function ongread(){
		$id=get("id","i");
		
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$shopid=get("shopid","i");
		$shop=MM("gread","gread_shop")->get($shopid,"shopid,imgurl,title");
		$where="  userid=".$userid." AND shopid=".$shopid." AND ukey='user' AND status in(1,2) ";
		$lastid=get("lastid","i");
		if($lastid){
			$where.=" AND id>".$lastid;
		}
		$start=get("per_page","i");
		$limit=12;
		$rscount=true;
		$list=M("mod_gread_guest")->select(array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if($list){
			$ids=array();
			foreach($list as $k=>$v){
				$v["nickname"]=$user["nickname"];
				$v["user_head"]=$user["user_head"];
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$ids[]=$v["id"];
				$list[$k]=$v;
			}
			array_multisort($list,$ids,SORT_ASC);
			M("mod_gread_guest")->update(array(
				"status"=>2
			),"id in("._implode($ids).")");
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"shop"=>$shop
		));
		$this->smarty->display("gread_guest/gread.html");
	}
	
	public function oncheckNew(){
		$shopid=get("shopid","i");
		$userid=M("login")->userid;
		$ctime=date("Y-m-d H:i:s",time()-36000);
		$row=M("mod_gread_guest")->selectRow("ukey='user' AND userid=".$userid." AND shopid=".$shopid." AND status=1 AND createtime>'".$ctime."' ");
		if($row){
			echo json_encode(array(
				"message"=>"success",
				"error"=>0,
				"num"=>1
			));
		}else{
			echo json_encode(array(
				"message"=>"error",
				"error"=>1,
				"num"=>0
			));
		}
	}
	
	public function onSave(){
		 
		$shopid=get_post("shopid","i");
		 
		$userid=M("login")->userid;
		$content=post("content","h");
		if(empty($content)){
			$this->goAll("请输入内容",1);
		}
		$data=array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"content"=>$content,
		 
			"author"=>"user",
			"status"=>1,
			"ukey"=>"user",
			"createtime"=>date("Y-m-d H:i:s")
		);
		M("mod_gread_guest")->insert($data);
		$data["ukey"]="shop";
		M("mod_gread_guest")->insert($data);
		//索引
		$row=M("mod_gread_guestindex")->delete("userid=".$userid." AND shopid=".$shopid);
		$data=array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"content"=>$content,
			"author"=>"user",
			"ukey"=>"user",
			 
			"createtime"=>date("Y-m-d H:i:s")
		);
		M("mod_gread_guestindex")->insert($data);
		$data["ukey"]="shop";
		M("mod_gread_guestindex")->insert($data);
		$this->goAll("保存成功");
	}
	
}
?>