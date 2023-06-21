<?php
class house_resource_loveControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onToggle(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$resid=get_post("resid","i");
		$row=M("mod_house_resource_love")->selectRow("userid=".$userid." AND resid=".$resid);
		if($row){
			$action="delete";
			M("mod_house_resource_love")->delete("id=".$row["id"]);
			M("mod_house_resource")->changenum("love_num",-1,"id=".$resid);
		}else{
			$action="add";
			M("mod_house_resource_love")->insert(array(
				"userid"=>$userid,
				"resid"=>$resid
			));
			M("mod_house_resource")->changenum("love_num",1,"id=".$resid);
		}
		$this->goAll("success",0,$action);
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=house_resource_love&a=my";
		$limit=1200;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where,
			"fields"=>"resid,userid"
		);
		$rscount=true;
		$list=M("mod_house_resource_love")->select($option,$rscount);
		if($list){
			$tids=array();
			foreach($list as $v){
				$tids[]=$v["resid"];
			}
			$tts=MM("house","house_resource")->getListByIds($tids); 
			
			foreach($list as $k=>$v){
		 
				$p=$tts[$v["resid"]];
				$list[$k]=$p;
			}
		} 
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"list"=>$list,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url
			)
		);
		$this->smarty->display("house_resource_love/my.html");
	}
}
?>