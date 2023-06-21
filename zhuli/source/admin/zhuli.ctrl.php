<?php
class zhuliControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
		 
	}
	
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	
	public function onDefault(){
		$url="/moduleadmin.php?m=zhuli";
		$where=" status in(0,1,2,3,4) ";
		$start=get('per_page','i');
		$limit=24;
		$rscount=true;
		$data=M("mod_zhuli")->select(array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC"
		),$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->assign(array(
			"pagelist"=>$pagelist,
			"rscount"=>$rscount,
			"data"=>$data,
			 
		));
		$this->smarty->display("zhuli/index.html");
	}
	
	public function onAdd(){
		 
		$id=get_post("id","i");
		if($id){
			$data=MM("zhuli","mod_zhuli")->selectRow("id=".$id);
			 			 
			$data['content']=MM("zhuli","mod_zhuli_data")->selectOne(array("where"=>"id=".$id,"fields"=>"content"));			 
		}else{
			$row=MM("zhuli","mod_zhuli")->selectRow("  is_temp=1");
			if(!$row){
				$id=MM("zhuli","mod_zhuli")->insert(array(
			 
					"is_temp"=>1,
					"status"=>99,
					"dateline"=>time()
				));
				MM("zhuli","mod_zhuli_data")->insert(array(
					"id"=>$id,
					"dateline"=>time()
				));
			}
		}
		$this->smarty->assign(array(
			"data"=>$data,
			 
		));
		$this->smarty->display("zhuli/add.html");
	}
	
	public function onSave(){
		$id=get_post('id','i');
		$starttime=strtotime($_POST['starttime']);
		$endtime=strtotime($_POST['endtime']);
		$data=MM("zhuli","mod_zhuli")->postData();
	 
		$content=post('content','x');
		$data['starttime']=$starttime;
		$data['endtime']=$endtime;
		if(!$id){
		 
			$id=MM("zhuli","mod_zhuli")->insert($data);
			MM("zhuli","mod_zhuli_data")->insert(array(
					"id"=>$id,
					"dateline"=>time(),
					"content"=>$content
			));
		}else{
			
			$row=MM("zhuli","mod_zhuli")->selectRow("id=".$id); 
			if($row['is_temp']){
				$data['is_temp']=0;
				$data['status']=1;
			}
			MM("zhuli","mod_zhuli")->update($data,"id=".$id);
			
			MM("zhuli","mod_zhuli_data")->update(array(
					"id"=>$id,
					"dateline"=>time(),
					"content"=>$content
			),"id=".$id);
		}
		$this->goall("保存成功");
	}
	
	public function onStatus(){
		$id=get_post("id","i");
		$row=MM("zhuli","mod_zhuli")->selectRow("id=".$id);
		if($row["status"]==1){
			$status=2;
		}else{
			$status=1;
		}
		MM("zhuli","mod_zhuli")->update(array("status"=>$status),"id=".$id);
		$this->goAll("保存成功",0,$status);
	}
	
	public function onDelete(){
		$id=get_post("id","i");
		$row=MM("zhuli","mod_zhuli")->selectRow("id=".$id);
		MM("zhuli","mod_zhuli")->update(array("status"=>11),"id=".$id);
		$this->goAll("删除成功",0);
	}
}

?>