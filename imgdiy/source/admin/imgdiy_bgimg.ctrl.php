<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class imgdiy_bgimgControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="";
			$url="/module.php?m=imgdiy_bgimg&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_imgdiy_bgimg")->select($option,$rscount);
			$catlist=MM("imgdiy","imgdiy_category")->id_title();
			if($data){
				foreach($data as $k=>$v){
					$v['catid_name']=$catlist[$v['catid']];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("imgdiy_bgimg/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_imgdiy_bgimg")->selectRow(array("where"=>"id={$id}"));
				
			}
			
			$catlist=M("mod_imgdiy_category")->select(array(
				"where"=>" status=1 "
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist
			));
			$this->smarty->display("imgdiy_bgimg/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_imgdiy_bgimg")->postData();
			if($id){
				M("mod_imgdiy_bgimg")->update($data,"id='$id'");
			}else{
				M("mod_imgdiy_bgimg")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_imgdiy_bgimg")->update(array("bstatus"=>$bstatus),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_imgdiy_bgimg")->update(array("bstatus"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>