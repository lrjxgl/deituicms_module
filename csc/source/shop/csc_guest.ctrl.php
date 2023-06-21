<?php
class csc_guestControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		 
	}
	public function onDefault(){
		$start=get("per_page","i");
		$limit=24; 
		$rscount=true;
		$list=M("mod_csc_guestindex")->select(array(
			"where"=>" shopid=".SHOPID ." AND ukey='shop'",
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
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"pagelist"=>$pagelist,
			"per_page"=>$per_page,
		));
		$this->smarty->display("csc_guest/index.html");
	}
	
	public function onUser(){
		$id=get("id","i");
		$product=M("mod_csc_product")->selectRow("id=".$id);
		$userid=get("userid","i");
		$shopid=SHOPID;
		$user=M("user")->getUser($userid);
		$this->smarty->goAssign(array(
			"product"=>$product,
			"shopid"=>$shopid,
			"user"=>$user
		));
		$this->smarty->display("csc_guest/user.html");
	}
	//根据分类获取对话 
	 
	public function oncsc(){
		$id=get("id","i");
		
		$userid=get("userid","i");
		$user=M("user")->getUser($userid);
		$shopid=SHOPID; 
		$shop=MM("csc","csc_shop")->get($shopid,"shopid,imgurl,shopname");
		$start=get("per_page","i");
		$limit=12;
		$rscount=true;
		$list=M("mod_csc_guest")->select(array(
			"where"=>" ukey='shop' AND userid=".$userid." AND shopid=".$shopid."  AND status in(1,2) ",
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		$newid=0;
		if($list){
			$newid=$list[0]["id"];
			foreach($list as $k=>$v){
				$v["nickname"]=$user["nickname"];
				$v["user_head"]=$user["user_head"];
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$list[$k]=$v;
				$ids[]=$v["id"];
			}
			M("mod_csc_guest")->update(array(
				"status"=>2
			),"id in("._implode($ids).")");
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount,
			"shop"=>$shop,
			"per_page"=>$per_page,
			"newid"=>$newid
		));
		$this->smarty->display("csc_guest/csc.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		$shopid=SHOPID;
		
		$userid=get_post("userid","i");
		$content=post("content","h");
		$data=array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"content"=>$content,
			"ukey"=>"user",
			"author"=>"shop",
			"status"=>1,
			"createtime"=>date("Y-m-d H:i:s")
		);
		M("mod_csc_guest")->insert($data);
		$data["ukey"]="shop";
		M("mod_csc_guest")->insert($data);
		//索引
		$row=M("mod_csc_guestindex")->delete("userid=".$userid." AND shopid=".$shopid);
		$data=array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"content"=>$content,
			"author"=>"shop",
			"ukey"=>"user",
			"createtime"=>date("Y-m-d H:i:s")
		);
		M("mod_csc_guestindex")->insert($data);
		$data["ukey"]="shop";
		M("mod_csc_guestindex")->insert($data);
		 
		$this->goAll("保存成功");
	}
	
	public function oncheckNew(){
		$shopid=SHOPID;
		$userid=get("userid","i");
		$ctime=date("Y-m-d H:i:s",time()-36000);
		$newid=get("newid","i");
		$row=M("mod_csc_guest")->selectRow("ukey='shop' AND userid=".$userid." AND shopid=".$shopid." AND status=1 AND id>'".$newid."' ");
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
	
}
?>