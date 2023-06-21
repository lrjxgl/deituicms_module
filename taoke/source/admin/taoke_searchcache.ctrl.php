<?php
class taoke_searchcacheControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$where=" status=1 ";
		$url="/moduleadmin.php?m=taoke_searchcache";
		$id=get("id","i");
		if($id){
			$where.=" AND id=".$id;
		}
		$word=get('word','h');
		if($word){
			$where.=" AND title like '%".$word."%' ";
			$url.="&word=".urlencode($word);
		}
		$xfrom=get("xfrom","h");
		if($xfrom){
			$where.=" AND k='".$xfrom."' ";
		}
		$status=get("status","h");
		 if($status){
			 $url.="&status=".$status;
			 switch($status){
				case "online":
					$where.=" AND status=1 ";
					break;
				default:
				$where.=" AND status in(0,2) ";
					break;
			 }
		 }
		$isrecommend=get("isrecommend","h");
		 if($isrecommend){
			 $url.="&isrecommend=".$isrecommend;
			 switch($isrecommend){
				case "online":
					$where.=" AND isrecommend=1 ";
					break;
				default:
				$where.=" AND isrecommend=0 ";
					break;
			 }
		 }
		$ishot=get("ishot","h");
		 if($ishot){
			 $url.="&ishot=".$ishot;
			 switch($ishot){
				case "online":
					$where.=" AND ishot=1 ";
					break;
				default:
				$where.=" AND ishot=0 ";
					break;
			 }
		 }   
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$list=M("mod_taoke_searchcache")->select($option,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$xfromlist=MM("taoke","taoke")->xfromList(); 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"pagelist"=>$pagelist,
			"xfromlist"=>$xfromlist
		));
		$this->smarty->display("taoke_searchcache/index.html");
	}
	
	public function onStatus(){
		$id=get_post('id',"i");
		$row=M("mod_taoke_searchcache")->selectRow("id=".$id);
		$status=1;
		if($row["status"]==1){
			$status=2;
		}
		M("mod_taoke_searchcache")->update(array(
			"status"=>$status
		),"id=".$id);
		$this->goAll("success",0,$status);
	}
	 
	public function onrecommend(){
		$id=get_post('id',"i");
		$row=M("mod_taoke_searchcache")->selectRow("id=".$id);
		$isrecommend=1;
		if($row["isrecommend"]==1){
			$isrecommend=0;
		}
		M("mod_taoke_searchcache")->update(array(
			"isrecommend"=>$isrecommend
		),"id=".$id);
		$this->goAll("success",0,$isrecommend);
		$this->goall("推荐修改成功");
	}
	
	public function onHot(){
		$id=get_post('id',"i");
		$row=M("mod_taoke_searchcache")->selectRow("id=".$id);
		$ishot=1;
		if($row["ishot"]==1){
			$ishot=0;
		}
		M("mod_taoke_searchcache")->update(array(
			"ishot"=>$ishot
		),"id=".$id);
		$this->goAll("success",0,$ishot);
		$this->goall("热门修改成功");
	}
	
	public function onDelete(){
		$id=get_post('id',"i");
		M("mod_taoke_searchcache")->update(array("status"=>11),"id=$id");
		$this->goAll("删除成功");
		 
	}
}
?>