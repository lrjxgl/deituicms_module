<?php
class car_guestControl extends skymvc{
	
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
		$list=M("mod_car_guestindex")->select(array(
			"where"=>" ukey='user' AND userid=".$userid,
			"order"=>" createtime DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if($list){
			foreach($list as $v){
				$shopids[]=$v["shopid"];
			}
			$sps=MM("car","car_shop")->getListByIds($shopids,"shopid,shopname,imgurl");
			
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
		$this->smarty->display("car_guest/index.html");
	}
	
	public function onShopIndex(){
		$start=get("per_page","i");
		$limit=24; 
		$rscount=true;
		$userid=M("login")->userid;
		$shop=M("mod_car_shop")->selectRow("userid=".$userid);
		if(empty($shop) || $shop["status"]!=1){
			$this->goAll("暂无权限",1);
		}
		$shopid=$shop["shopid"];
		$list=M("mod_car_guestindex")->select(array(
			"where"=>" ukey='shop' AND shopid=".$shopid,
			"order"=>" createtime DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			
			foreach($list as $k=>$v){
				
				$v["user"]=$us[$v["userid"]];
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
		$this->smarty->display("car_guest/shopIndex.html");
	}
	
	public function onUser(){
		$productid=get("productid","i");
		$product=M("mod_car_product")->selectRow("productid=".$productid);
		$userid=M("login")->userid;
		$shopid=get("shopid","i");
		$shop=M("mod_car_shop")->selectRow("shopid=".$shopid);
		if(empty($shop) || $shop["status"]!=1){
			$this->goAll("暂无权限",1);
		}
		$shop["imgurl"]=images_site($shop["imgurl"]);
		$user=M("user")->getUser($userid);
		$start=get("per_page","i");
		$limit=96;
		$rscount=true;
		$where=" ukey='user' AND userid=".$userid." AND shopid=".$shopid."  AND status in(0,1,2) ";
		 
		$list=M("mod_car_guest")->select(array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		 
		if($list){
			foreach($list as $k=>$v){
				 
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$list[$k]=$v;
				$ids[]=$v["id"];
			}
			M("mod_car_guest")->update(array(
				"status"=>2
			),"id in("._implode($ids).")");
			$nList=array();
			$num=count($list)-1;
			for($i=$num;$i>=0;$i--){
				$nList[]=$list[$i];
			}
			$list=$nList;
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"product"=>$product,
			"shop"=>$shop,
			"user"=>$user,
			"per_page"=>$per_page,
			"list"=>$list
		));
		$this->smarty->display("car_guest/user.html");
	}
	public function onShop(){
		$id=get("id","i");
		$product=M("mod_car_product")->selectRow("productid=".$id);
		$userid=get("userid","i");
		$ssuserid=M("login")->userid;
		$shop=M("mod_car_shop")->selectRow("userid=".$ssuserid);
		
		if(empty($shop) || $shop["status"]!=1){
			$this->goAll("暂无权限",1);
		}
		$shop["imgurl"]=images_site($shop["imgurl"]);
		$shopid=$shop["shopid"];
		$user=M("user")->getUser($userid);
		
		$start=get("per_page","i");
		$limit=96;
		$rscount=true;
		$where=" ukey='shop' AND userid=".$userid." AND shopid=".$shopid."  AND status in(0,1,2) ";
		 
		$list=M("mod_car_guest")->select(array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		 
		if($list){
			foreach($list as $k=>$v){
				 
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$list[$k]=$v;
				$ids[]=$v["id"];
			}
			M("mod_car_guest")->update(array(
				"status"=>2
			),"id in("._implode($ids).")");
			$nList=array();
			$num=count($list)-1;
			for($i=$num;$i>=0;$i--){
				$nList[]=$list[$i];
			}
			$list=$nList;
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"product"=>$product,
			"shopid"=>$shopid,
			"user"=>$user,
			"rscount"=>$rscount,
			"shop"=>$shop,
			"per_page"=>$per_page,
			"list"=>$list,
		));
		$this->smarty->display("car_guest/shop.html");
	}
	 
	 
	
	public function oncheckNew(){
		$shopid=get("shopid","i");
		$userid=get_post("userid","i");
		if(!$userid){
			$userid=M("login")->userid; 
		} 
		$ctime=date("Y-m-d H:i:s",time()-36000);
		$ukey=get("ukey","h");
		if(!$ukey){
			$ukey="user";
		}
		$row=M("mod_car_guest")->selectRow("ukey='".$ukey."' AND userid=".$userid." AND shopid=".$shopid." AND status=0 AND createtime>'".$ctime."' ");
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
		$productid=get("productid","i");
		$product=M("mod_car_product")->selectRow("productid=".$productid);
		$userid=post("userid","i");
		if(!$userid){
			$userid=M("login")->userid;
			$author="user";
		}else{
			$author="shop";
		}
		
		
		$content=post("content","h");
		$data=array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"content"=>$content,
			"productid"=>$id,
			"author"=>$author,
			
			"ukey"=>"user",
			"createtime"=>date("Y-m-d H:i:s")
		);
		if($author=="user"){
			$data["status"]=1;
		}
		M("mod_car_guest")->insert($data);
		$data["ukey"]="shop";
		$data["status"]=0;
		if($author=="shop"){
			$data["status"]=1;
		}
		M("mod_car_guest")->insert($data);
		//索引
		$row=M("mod_car_guestindex")->delete("userid=".$userid." AND shopid=".$shopid);
		$data=array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"content"=>$content,
			"author"=>$author,
			"ukey"=>"user",
			"productid"=>$id,
			"createtime"=>date("Y-m-d H:i:s")
		);
		M("mod_car_guestindex")->insert($data);
		$data["ukey"]="shop";
		M("mod_car_guestindex")->insert($data);
		$this->goAll("保存成功");
	}
	
}
?>