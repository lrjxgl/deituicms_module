<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_user_tagControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/moduleadmin.php?m=zbtao_user_tag&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zbtao_user_tag")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("zbtao_user_tag/index.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$pp=M("login")->getUser();
			$list=M("mod_zbtao_tag")->select(array(
				"where"=>"status=1",
				"order"=>"orderindex ASC"
			));
			$tagids=M("mod_zbtao_user_tag")->selectCols(array(
				"where"=>" userid=".$pp["userid"],
				"fields"=>"tagid"
			)); 
			if(!empty($list)){
				foreach($list as $k=>$v){
					if(!empty($tagids) && in_array($v["tagid"],$tagids)){
						$v["selected"]=1;
					}else{
						$v["selected"]=0;
					}
					$list[$k]=$v;
				}
			}
			$this->smarty->goassign(
				array(
					"list"=>$list,
					 
					"url"=>$url
				)
			); 
			$this->smarty->display("zbtao_user_tag/my.html");
		}
		
		public function onToggle(){
			M("login")->checkLogin();
			$pp=M("login")->getUser();
			$tagid=get("tagid","i");
			$row=M("mod_zbtao_user_tag")->selectRow("userid=".$pp["userid"]." AND tagid=".$tagid);
			if($row){
				M("mod_zbtao_user_tag")->delete("userid=".$pp["userid"]." AND tagid=".$tagid);
			}else{
				$count=M("mod_zbtao_user_tag")->selectOne(array(
					"where"=>"userid=".$pp["userid"],
					"fields"=>"count(*) as ct"
				));
				if($count>5){
					$this->goAll("只能标记5个标签",1);
				}
				M("mod_zbtao_user_tag")->insert(array(
					"userid"=>$pp["userid"],
					"tagid"=>$tagid
				));
			}
			$this->goall("保存成功");
		}
		
		public function onDelete(){
			M("login")->checkLogin();
			$pp=M("login")->getUser();
			$id=get_post('id',"i");
			M("mod_zbtao_user_tag")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>