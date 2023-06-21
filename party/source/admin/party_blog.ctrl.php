<?php
class party_blogControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$start=get("per_page","i");
		$limit=24;
		$type=get("type","h");
		switch($type){
			case "recommend":
				$where=" isrecommend=1 ";
				
				break;
			case "new":
				$where=" status=0 ";
				break;
			default:
				
				$where=" status in(0,1,2) ";
				break;
		}
		$ops=array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("party","party_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"type"=>$type
		));
		$this->smarty->display("party_blog/index.html");
	}
	public function onAdd(){
		$id=get("id","i");
		$data=M("mod_party_blog")->selectRow("id=".$id);
		$data["trueimgurl"]=images_site($data["imgurl"]);
		$arr=explode(",",$data["imgsdata"]);
		$imgsdata=[];
		foreach($arr as $v){
			$imgsdata[]=array(
				"imgurl"=>$v,
				"trueimgurl"=>images_site($v)
			);
		}
		$this->smarty->goAssign(array(
			"data"=>$data,
			"imgsdata"=>$imgsdata
		));
		$this->smarty->display("party_blog/add.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		$data=M("mod_party_blog")->postData();
		M("mod_party_blog")->update($data,"id=".$id);
		$this->goAll("success");
	}
	
	public function onDelete(){
 
		$id=get("id","i");
		 
		M("mod_party_blog")->update(array("status"=>11,"isrecommend"=>0),"id=".$id);
		$this->goAll("删除成功");
	}
	public function onStatus(){
		$id=get_post('id',"i");
		$row=M("mod_party_blog")->selectRow("id=".$id);
		$status=1;
		if($row["status"]==1){
			$status=2;
		}
		M("mod_party_blog")->update(array(
			"status"=>$status
		),"id=".$id);
		$this->goAll("success",0,$status);
	}
	public function onRecommend(){
		$id=get_post('id',"i");
		$row=M("mod_party_blog")->selectRow("id=".$id);
		$status=1;
		if($row["isrecommend"]==1){
			$status=2;
		}
		M("mod_party_blog")->update(array(
			"isrecommend"=>$status
		),"id=".$id);
		$this->goAll("success",0,$status);
	}
}