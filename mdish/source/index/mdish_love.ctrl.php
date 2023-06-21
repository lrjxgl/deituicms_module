<?php
class mdish_loveControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$userid=M("login")->userid;
		$productid=get("productid","i");
		$uids=M("mod_mdish_love")->selectCols(array(
			"where"=>"productid=".$productid,
			"fields"=>"userid",
			"order"=>"id ASC"
		));
		$list=array();
		if($uids){
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($uids as $uid){
				$list[]=$us[$uid];
			}
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("mdish_love/index.html");
		
	}
	
	public function onMy(){
		$userid=M("login")->userid;
	 
		$res=M("mod_mdish_love")->select(array(
			"where"=>"userid=".$userid,
			"fields"=>"productid,shopid",
			"order"=>"id ASC"
		));
		$pids=array();
		$spids=array();
		$list=array();
		if($res){
			foreach($res as $rs){
				$pids[]=$rs["productid"];
				$spids[]=$rs["shopid"];
			}
			$pros=MM("mdish","mdish_product")->getListByIds($pids);
			$shops=MM("mdish","mdish_shop")->getListByIds($spids,"shopid,title");
			 
			foreach($res as $rs){
				$v=$pros[$rs["productid"]];
				$v["shop"]=$shops[$rs["shopid"]];
				$list[]=$v;
				
			}
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("mdish_love/my.html");
		
	}
	
	public function onToggle(){
		$userid=M("login")->userid;
		$productid=get("productid","i");
		$product=M("mod_mdish_product")->selectRow("productid=".$productid);
		$row=M("mod_mdish_love")->selectRow("userid=".$userid." AND productid=".$productid);
		$num=0;
		if($row){
			M("mod_mdish_love")->delete("userid=".$userid." AND productid=".$productid);
			$num=$product["love_num"]-1;
			M("mod_mdish_product")->update(array(
				"love_num"=>$product["love_num"]-1
			),"productid=".$productid);
			$action="delete";
		}else{
			M("mod_mdish_love")->insert(array(
				"userid"=>$userid,
				"productid"=>$productid,
				"shopid"=>$product["shopid"],
				"dateline"=>time()
			));
			$num=$product["love_num"]+1;
			M("mod_mdish_product")->update(array(
				"love_num"=>$product["love_num"]+1
			),"productid=".$productid);
			$action="add";
		}
		$this->goAll("success",0,array(
			"action"=>$action,
			"num"=>$num
		));
		
	}
	
}
?>