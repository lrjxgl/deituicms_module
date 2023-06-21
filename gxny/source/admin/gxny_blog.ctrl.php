<?php
class gxny_blogControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$start=get("per_page","i");
		$limit=24;
		$type=get("type","h");
		$type_name="全部帖子";
		switch($type){
			case "new":
				$where=" status=0 ";
				$status_name="待审帖子";
				break;
			case "pass":
				$where=" status=1 ";
				$status_name="上架帖子";
				break;
			case "forbid":
				$where=" status=2 ";
				$status_name="下架帖子";
				break;
			case "recommend":
				$where=" isrecommend=1 AND status=1 ";
				$status_name="推荐帖子";
				break;
			case "hot":
				$where=" status=1 AND createtime>'".date("Y-m-d H:i:s",strtotime("-10 day"))."' ";
				$order="comment_num DESC";
				$status_name="热门帖子";
				break;	
			default:
				
				$where=" status in(0,1,2) ";
				break;
		}
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND content like '%".$keyword."%' ";
		}
		$ops=array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("gxny","gxny_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"type"=>$type,
			"type_name"=>$type_name
		));
		$this->smarty->display("gxny_blog/index.html");
	}
	
	public function onDelete(){
 
		$id=get("id","i");
		 
		M("mod_gxny_blog")->update(array("status"=>11,"isrecommend"=>0),"id=".$id);
		$this->goAll("删除成功");
	}
	public function onStatus(){
		$id=get_post('id',"i");
		$row=M("mod_gxny_blog")->selectRow("id=".$id);
		$status=1;
		if($row["status"]==1){
			$status=2;
		}
		M("mod_gxny_blog")->update(array(
			"status"=>$status
		),"id=".$id);
		$this->goAll("success",0,$status);
	}
	public function onRecommend(){
		$id=get_post('id',"i");
		$row=M("mod_gxny_blog")->selectRow("id=".$id);
		$status=1;
		if($row["isrecommend"]==1){
			$status=2;
		}
		M("mod_gxny_blog")->update(array(
			"isrecommend"=>$status
		),"id=".$id);
		$this->goAll("success",0,$status);
	}
	public function onSendGold(){
		$id=get_post('id',"i");
		$row=M("mod_gxny_blog")->selectRow("id=".$id);
		$gold=get("gold","i");
		M("user")->addMoney(array(
			"userid"=>$row["userid"],
			"gold"=>$gold,
			"content"=>"您的帖子被打赏了{$gold}个金币"
		));
		M("mod_gxny_blog")->update(array(
			"gold"=>$row["gold"]+$gold
		),"id=".$id);
		$this->goAll("打赏成功",0,$row["gold"]+$gold);
	}
}