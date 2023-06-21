<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class im_msgControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=im_msg&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_im_msg")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v["userid"];
					$uids[]=$v["touserid"];
				}
				$us=M("user")->getUserByIds($uids,"userid,nickname");
				 
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["to_nickname"]=$us[$v["touserid"]]["nickname"]; 
					$v["content"]=MM("im","im_msg")->parse($v["content"]);
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
					"url"=>$url
				)
			);
			$this->smarty->display("im_msg/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_im_msg")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("im_msg/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_im_msg")->postData();
			if($id){
				M("mod_im_msg")->update($data,"id='$id'");
			}else{
				M("mod_im_msg")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_im_msg")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_im_msg")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
		
		
	}

?>