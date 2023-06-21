<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class sjsj_tagsControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="module.php?m=sjsj_tags&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tagid DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_sjsj_tags")->select($option,$rscount);
			//已关注标签
			$userid=M("login")->userid;
			$nlist=[];
			if($userid){
				$tagids=MM("sjsj","sjsj_user_tags")->selectCols(array(
					"where"=>"userid=".$userid,
					"fields"=>"tagid"
				));
				if(!empty($tagids) && !empty($list)){
					foreach($list as $k=>$v){
						if(!in_array($v["tagid"],$tagids)){
							$nlist[]=$v;
						}
					}
				}
			}
			
			
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$nlist,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("sjsj_tags/index.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="module.php?m=sjsj_tags&a=my";
			$limit=120;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tagid DESC",
				"where"=>$where,
				"fields"=>"tagid"
			);
			$rscount=true;
			$tagids=MM("sjsj","sjsj_user_tags")->selectCols($option,$rscount);
			
			$list=[];
			if(!empty($tagids)){
				$tags=MM("sjsj","sjsj_tags")->getListByIds($tagids);
				foreach($tagids as $tagid){
					$list[]=$tags[$tagid];
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
			$this->smarty->display("sjsj_tags/index.html");
		}
		/*关注*/
		public function onToggle(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$tagid=get("tagid","i");
			$tagids=post("tagids","h");
			$arr=[];
			if(!empty($tagid)){
				$arr[]=$tagid;
			}elseif(!empty($tagids)){
				$e=explode(",",$tagids);
				foreach($e as $v){
					$arr[]=intval($v);
				}
			}
			if(empty($arr)){
				$this->goAll("请选择标签",1);
			}
			foreach($arr as $tagid){
				$row=MM("sjsj","sjsj_user_tags")->selectRow("userid=".$userid." AND tagid=".$tagid);
				if(empty($row)){
					MM("sjsj","sjsj_user_tags")->insert(array(
						"userid"=>$userid,
						"tagid"=>$tagid
					));
				}else{
					MM("sjsj","sjsj_user_tags")->delete("userid=".$userid." AND tagid=".$tagid);
				}
			}
			
			$this->goAll("success");
		}
	}

?>