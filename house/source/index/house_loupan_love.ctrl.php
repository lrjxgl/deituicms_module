<?php
class house_loupan_loveControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onToggle(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$lpid=get_post("lpid","i");
		$row=M("mod_house_loupan_love")->selectRow("userid=".$userid." AND lpid=".$lpid);
		if($row){
			$action="delete";
			M("mod_house_loupan_love")->delete("id=".$row["id"]);
			M("mod_house_loupan")->changenum("love_num",-1,"id=".$lpid);
		}else{
			$action="add";
			M("mod_house_loupan_love")->insert(array(
				"userid"=>$userid,
				"lpid"=>$lpid
			));
			M("mod_house_loupan")->changenum("love_num",1,"id=".$lpid);
		}
		$this->goAll("success",0,$action);
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=house_loupan_love&a=my";
		$limit=1200;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where,
			"fields"=>"lpid,userid"
		);
		$rscount=true;
		$list=M("mod_house_loupan_love")->select($option,$rscount);
		if($list){
			$tids=array();
			foreach($list as $v){
				$tids[]=$v["lpid"];
			}
			$tts=MM("house","house_loupan")->getListByIds($tids); 
			
			foreach($list as $k=>$v){
		 
				$p=$tts[$v["lpid"]];
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
		$this->smarty->display("house_loupan_love/my.html");
	}
}
?>