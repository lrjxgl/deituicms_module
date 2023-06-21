<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class party_blog_commentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=party_blog_comment&a=default";
			$type=get("type","h");
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
				default:
					$where=" status in(0,1,2)";
					break;
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
			$data=M("mod_party_blog_comment")->select($option,$rscount);
			if($data){
				$uids=array();
				foreach($data as $k=>$v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"type"=>$type
				)
			);
			$this->smarty->display("party_blog_comment/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_party_blog_comment")->selectRow("id=".$id);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_party_blog_comment")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_party_blog_comment")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>