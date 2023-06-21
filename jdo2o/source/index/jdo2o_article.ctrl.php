<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jdo2o_articleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" shopid=".SHOPID." AND status=1 ";
			$url="/module.php?m=jdo2o_article&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("jdo2o","jdo2o_article")->Dselect($option,$rscount);
			 
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
			$this->smarty->display("jdo2o_article/index.html");
		}
		
		public function onList(){
			$where="  shopid=".SHOPID." AND status=1 ";
			$url="/module.php?m=jdo2o_article&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("jdo2o","jdo2o_article")->Dselect($option,$rscount);
		 
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
			$this->smarty->display("jdo2o_article/index.html");
		}
		
		public function onCity(){
			$where="   status=1 ";
			$url="/module.php?m=jdo2o_article&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("jdo2o","jdo2o_article")->Dselect($option,$rscount);
		 
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
			$this->smarty->display("jdo2o_article/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_jdo2o_article")->selectRow(array("where"=>"id=".$id." AND status=1 "));
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$userid=M("login")->userid;
			//图集
			$imgslist=array();
			if($data['imgsdata']){
				$imgs=explode(",",$data['imgsdata']);
				foreach($imgs as $img){
					$imgslist[]=images_site($img);
				}			  
			}
			//是否点赞
			$islove=0;
			$love=M("love")->selectRow("tablename='mod_jdo2o_article' AND userid=".$userid." AND objectid=".$id);
			if($love){
				$islove=1;
			}
			//是否收藏
			$isfav=0;
			if($userid){
				$fav=M("fav")->selectRow("tablename='mod_jdo2o_article' AND userid=".$userid." AND objectid=".$id);
				if($fav){
					$isfav=1;
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"islove"=>$islove,
				"isfav"=>$isfav,
				"comment_objectid"=>$id,
				"comment_tablename"=>"mod_jdo2o_article",
				"comment_f_userid"=>$data['userid'],
				
			));
			$this->smarty->display("jdo2o_article/show.html");
		}
		
		public function onAddClick(){
			$id=get("id");
			$userid=M("login")->userid;
			M("mod_jdo2o_article")->changenum("view_num",1,"id=".$id);
		}
		
	}

?>