<?php
class fxa_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		if(!in_array(get("a"),array("login","loginsave","checkorder","checkordersave"))){
			if(!isset($_SESSION["ssfxaadmin"]["shopid"])){
				$this->onLogin();
			}
		}
		
	}
	 
	public function onDefault(){
		
		$this->smarty->display("fxa_shop/order.html");
		
	}
	public function onLogin(){
		$this->smarty->display("fxa_shop/login.html");
	}
	public function onLoginSave(){
		$username=post("username","h");
		$password=post("password","h");
		$admin=M("mod_fxa_shop_admin")->selectRow("adminname='".$username."'");
		if(empty($admin)){
			$this->goAll("账号密码错误",1,$admin);
		}
		if($admin["password"]!=umd5($password.$admin["salt"])){
			$this->goAll("密码错误",1);
		}
		unset($admin["password"]);
		$_SESSION["ssfxaadmin"]=$admin;	
		$this->goAll("登录成功");
	}
	public function onOrder(){
		 
		$shopid=$_SESSION["ssfxaadmin"]["shopid"];	
		$where=" ispay=1 AND shopid=".$shopid;
		$url="/module.php?m=fxa_shop&a=order";
		$limit=20;
		$start=get("per_page","i");
		$type=get("type","h");
		switch($type){
			case "finish":
				$where.=" AND status=1 ";
				break;
			case "unfinish":
				$where.=" AND status in(0,2) ";
				break;
		}
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$list=MM("fxa","fxa_order")->Dselect($option,$rscount);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
		));
		$this->smarty->display("fxa_shop/order.html");
	} 
	public function onCheckOrder(){
		$yzm=get("yzm","h");
		//生成二维码
		require "extends/hashids/Hashids.php";
		$hashids = new Hashids\Hashids(date("Y-m-d H"),8);
		$dec=$hashids->decode($yzm);
		 
		if(empty($dec)){
			$this->goAll("二维码出错，请让用户刷新二维码",0,0,"/module.php?m=fxa_shop");
		}
		$orderid=$dec[0];
		$data=M("mod_fxa_order")->selectRow(array("where"=>"orderid=".$orderid));
		$product=M("mod_fxa_product")->selectRow("id=".$data["productid"]);
		$product["imgurl"]=images_site($product["imgurl"]);
		$this->smarty->goAssign(array(
			"order"=>$data,
			"product"=>$product,
			"yzm"=>$yzm,
			"ssfxadmin"=>$_SESSION["ssfxaadmin"]
		));
		$this->smarty->display("fxa_shop/checkorder.html");
	}
	public function onCheckOrderFinish(){
		if(empty($_SESSION["ssfxaadmin"])){
			$username=post("username","h");
			$password=post("password","h");
			$admin=M("mod_fxa_shop_admin")->selectRow("adminname='".$username."'");
			if(empty($admin)){
				$this->goAll("账号密码错误",1,$admin);
			}
			if($admin["password"]!=umd5($password.$admin["salt"])){
				$this->goAll("密码错误",1);
			}
			unset($admin["password"]);
			$_SESSION["ssfxaadmin"]=$admin;		 
		}
		$yzm=get("yzm","h");
		//生成二维码
		require "extends/hashids/Hashids.php";
		$hashids = new Hashids\Hashids(date("Y-m-d H"),8);
		$dec=$hashids->decode($yzm);
		if(empty($dec)){
			$this->goAll("数据出错",1);
		}
		$orderid=$dec[0];
		$order=M("mod_fxa_order")->selectRow(array("where"=>"orderid=".$orderid));
		if($order["shopid"]!=$_SESSION["ssfxaadmin"]["shopid"]){
			$this->goAll("您无权限处理该订单",1);
		}
		if($order["status"]!=0 || $order["send_type"]!=1){
			$this->goAll("该订单已经处理过了",1);
		}
		M("mod_fxa_order")->update(array(
			"status"=>3,
			"usetime"=>time()
		),"orderid=".$orderid);
		//处理返利
		if($order["invite_userid"]){
			MM("fxa","fxa_ushare")->add(array(
				"orderid"=>$orderid,
				"userid"=>$order["invite_userid"],
				"money"=>$order["fx_money"],
				"productid"=>$order["productid"],
				"shopid"=>$order["shopid"]
			));
		}
		$this->goAll("保存成功");
	}
	
}
?>