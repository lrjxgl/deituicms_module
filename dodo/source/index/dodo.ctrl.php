<?php
class dodoControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		 
		if(!isset($_SESSION["ssuser"]) || empty($_SESSION["ssuser"])){
			
			if(INWEIXIN){
				$backurl=urlencode(HTTP_HOST.$_SERVER["REQUEST_URI"]);
				header("Location: /index.php?m=open_weixin&a=Geturl&backurl=".$backurl);
				exit;
			}else{
				header("Location: /index.php?m=login");
				exit;
			}
			
		}
	}
	public function onDefault(){
		
		$list=M("mod_dodo")->select(array(
			"where"=>" status=0 AND etime>".time()." ",
			"limit"=>24,
			"order"=>"id DESC"
		));
		 
		 $seo=M("seo")->get("dodo","index");
		$this->smarty->assign(array(
			"list"=>$list,
		 
			"seo"=>$seo
		));
		$this->smarty->display("index.html");
	}
	public function onShow(){
		 
		$userid=M("login")->userid;
		$id=get("id","i");
		$data=M("mod_dodo")->selectRow("id=".$id." AND status=0 ");
		if(empty($data)){
			$this->goAll("数据出错",1);
		}
		$data["isauthor"]=0;
		if($userid==$data["userid"]){
			$data["isauthor"]=1;
		}
		$ltime=$data["etime"]-time();
		$data["ltime"]=$ltime;
		$user=M("user")->getUser($data["userid"]);
		$site=M("site")->get();
		$seo=array(
			"title"=>$user["nickname"]."要在".date("Y-m-d",$data["etime"])."前完成任务：".str_replace("\n",",",$data["content"]),
			"imgurl"=>$site["logo"]
		);
		
		$love=M("mod_dodo_love")->selectRow("userid=".$userid." AND doid=".$id);
		$islove=0;
		if($love){
			$islove=1;
		}
		
		$this->smarty->assign(array(
			"data"=>$data,
			"seo"=>$seo,
			"user"=>$user,
			"ssuser"=>$userid,
			"islove"=>$islove
		));
		$this->smarty->display("dodo/show.html");
	}
	public function onHome(){
		$userid=M("login")->userid;
		$this->smarty->display("dodo/home.html");
	}
	public function onMy(){
		$userid=M("login")->userid;
		$list=M("mod_dodo")->select(array(
			"where"=>" status=0 AND userid=".$userid,
			"limit"=>24,
			"order"=>"id DESC"
		)); 
		$this->smarty->assign(array(
			"list"=>$list
		));
		$this->smarty->display("dodo/my.html");
	}
	public function onAdd(){
		$this->smarty->display("dodo/add.html");
	}
	public function onSave(){
		$userid=M("login")->userid;
		$content=post("content","h");
		$etime=strtotime(post("etime"));
		$row=M("mod_dodo")->selectRow(array(
			"where"=>" userid=".$userid,
			"order"=>"id DESC"
		));
		if($row && time()-$row["dateline"]<3600){
			$this->goAll("一小时只能发布一条",1);
		}
		$id=M("mod_dodo")->insert(array(
			"userid"=>$userid,
			"content"=>$content,
			"etime"=>$etime,
			"dateline"=>time()
		));
		$this->goAll("保存成功",0,$id,"/module.php?m=dodo&a=show&id=".$id);
	}
}