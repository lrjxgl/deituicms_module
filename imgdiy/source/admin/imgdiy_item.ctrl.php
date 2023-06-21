<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class imgdiy_itemControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$imgid=get("imgid","i");
			$where=" status=2 AND  imgid=".$imgid;
			$url="/moduleadmin.php?m=imgdiy_item&a=default";
			$limit=200;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_imgdiy_item")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					 
					if($v['type']=="img"){
						$v['trueimgurl']=images_site($v['imgurl']);
					}
					 
					
					$data[$k]=$v;
				}
			}
			$fonts=MM("imgdiy","imgdiy")->fonts;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"fontList"=>$fonts
				)
			);
			$this->smarty->display("imgdiy_item/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_imgdiy_item")->selectRow(array("where"=>"id={$id}"));
				$imgtpl=str2arr($data['imgtpl']);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgtpl"=>$imgtpl
			));
			$this->smarty->display("imgdiy_item/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$data=M("mod_imgdiy_item")->postData();	
			if($id){
				$data["status"]=2;
				M("mod_imgdiy_item")->update($data,"id='$id'");
			}else{
				$data["status"]=2;
				M("mod_imgdiy_item")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_imgdiy_item")->update(array("status"=>$bstatus),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_imgdiy_item")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>