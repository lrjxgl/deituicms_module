<?php
class olprint_adminControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shopid=get("shopid","i");
		$shop=M("mod_olprint_shop")->selectRow("shopid=".$shopid);
		if(empty($shop)){
			$this->goAll("商家不能为空",1);
		}
		$where=" shopid=".$shopid;
		$url="/moduleadmin.php?m=olprint_admin&a=default";
		$limit=24;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" adminid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_olprint_admin")->select($option,$rscount);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"shop"=>$shop,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url
			)
		);
		$this->smarty->display("olprint_admin/index.html");	
	}
	public function onAdd(){
		$this->smarty->display("olprint_admin/add.html");	
	}
	public function onSave(){
		$shopid=get_post("shopid","i");
		$shop=M("mod_olprint_shop")->selectRow("shopid=".$shopid);
		if(empty($shop)){
			$this->goAll("商家不能为空",1);
		}
		$adminname=post("adminname","h");
		if(empty($adminname)){
			$this->goAll("请填写用户名",1);
		}
		//判断是否已有
		$row=M("mod_olprint_admin")->selectRow("adminname='".$adminname."'");
		if($row){
			$this->goAll("账号已经存在了",1,$row);
		}
		$password=post("password","h");
		$salt=rand(1000,9999);
		$data=array(
			"password"=>umd5($password.$salt),
			"salt"=>$salt,
			 "adminname"=>$adminname,
			 "shopid"=>$shopid
		);
		M("mod_olprint_admin")->insert($data);
		$this->goAll("添加成功");
	}
	public function onPassword(){
		
	}
	public function onPasswordSave(){
		$password=post("password","h");
		$password2=post("password2","h");
		$salt=rand(1000,9999);
		$adminid=get_post("adminid","i");
		if(empty($password)){
			$this->goAll("请输入密码",1);
		}
		if($password!=$password2){
			$this->goAll("密码不一致",1);
		}
		$data=array(
			"password"=>umd5($password.$salt),
			"salt"=>$salt,
			 
		);
		M("mod_olprint_admin")->update($data,"adminid=".$adminid);
		$this->goAll("密码修改成功");
	}
}