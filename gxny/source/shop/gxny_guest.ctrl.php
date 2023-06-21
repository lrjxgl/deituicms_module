<?php
class gxny_guestControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		 
	}
	public function onDefault(){
		 
		$list=M("mod_gxny_guestindex")->select(array(
			"where"=>" shopid=".SHOPID ,
			"order"=>" createtime DESC"
		));
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
	 
		$this->smarty->goAssign(array(
			"list"=>$list,			 
		));
		$this->smarty->display("gxny_guest/index.html");
	}
	
	public function onUser(){
		$id=get("id","i");
		$product=M("mod_gxny_product")->selectRow("id=".$id);
		$userid=get("userid","i");
		$shopid=SHOPID;
		$user=M("user")->getUser($userid);
		$this->smarty->goAssign(array(
			"product"=>$product,
			"shopid"=>$shopid,
			"user"=>$user
		));
		$this->smarty->display("gxny_guest/user.html");
	}
	//根据分类获取对话 
	 
	public function ongxny(){
		$id=get("id","i");
		$product=M("mod_gxny_product")->selectRow("id=".$id);
		$userid=get("userid","i");
		$user=M("user")->getUser($userid);
		$shopid=SHOPID; 
		$shop=MM("gxny","gxny_shop")->get($shopid,"shopid,imgurl,shopname");
		$list=M("mod_gxny_guest")->select(array(
			"where"=>" (userid=".$shopid." or shopid=".$shopid.") AND productid=".$id." AND status=1 ",
			"order"=>" id DESC"
		));
		if($list){
			foreach($list as $k=>$v){
				$v["nickname"]=$user["nickname"];
				$v["user_head"]=$user["user_head"];
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$list[$k]=$v;
			}
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list,
			"product"=>$product,
			"shop"=>$shop
		));
		$this->smarty->display("gxny_guest/gxny.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		$shopid=SHOPID;
		$product=M("mod_gxny_product")->selectRow("id=".$id);
		$userid=get_post("userid","i");
		$content=post("content","h");
		M("mod_gxny_guest")->insert(array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"content"=>$content,
			"productid"=>$id,
			"author"=>"shop",
			"status"=>1,
			"createtime"=>date("Y-m-d H:i:s")
		));
		//索引
		$row=M("mod_gxny_guestindex")->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($row){
			M("mod_gxny_guestindex")->update(array(
				"userid"=>$userid,
				"shopid"=>$shopid,
				"content"=>$content,
				"productid"=>$id,
				"author"=>"shop",
				"createtime"=>date("Y-m-d H:i:s")
			),"id=".$row["id"]);
		}else{
			M("mod_gxny_guestindex")->insert(array(
				"userid"=>$userid,
				"shopid"=>$shopid,
				"content"=>$content,
				"author"=>"shop",
				"productid"=>$id,
				"createtime"=>date("Y-m-d H:i:s")
			));
		}
		 
		$this->goAll("保存成功");
	}
	
}
?>