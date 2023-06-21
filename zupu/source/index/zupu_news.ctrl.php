<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zupu_newsControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zupu_news&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zupu_news")->select($option,$rscount);
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
			$this->smarty->display("zupu_news/index.html");
		}
		
		public function onHome(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zupu_news&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zupu","zupu_news")->Dselect($option,$rscount);
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
			$this->smarty->display("zupu_news/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_zupu_news")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zupu_news/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_zupu_news")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zupu_news/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			$data=M("mod_zupu_news")->postData();
			$data["userid"]=M("login")->userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			if($id){
				M("mod_zupu_news")->update($data,"id='$id'");
			}else{
				M("mod_zupu_news")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_zupu_news")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_zupu_news")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>