<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cj1Control extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		
		public function onDefault(){
			$where=" 1 ";
			$url="/moduleadmin.php?m=cj1";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cj1")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("cj1/index.html");
		}
		
		public function onCopy(){
			$id=get('id','i');
			$data=M("mod_cj1")->selectRow(array("where"=>"id={$id}"));
			$uns=array("id","join_num","isfinish","win_userid");
			foreach($uns as $key){
				unset($data[$key]);
			}
			$data['title'].="第二期";
			$data['starttime']=time()+3600;
			M("mod_cj1")->insert($data);
			$this->goAll("复制成功");	
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_cj1")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("cj1/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$starttime=strtotime(post('starttime'));
			$data=M("mod_cj1")->postData();
			$data['starttime']=$starttime;
			if($id){
				M("mod_cj1")->update($data,"id='$id'");
			}else{
				$data['dateline']=time();
				M("mod_cj1")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_cj1")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_cj1")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>