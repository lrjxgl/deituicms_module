<?php
	class flk_videoControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) AND shopid=".SHOPID;
			$url="/shopadmin.php?m=flk_video";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
			}
			$option=array(
				"where"=>$where,
				"order"=>"id DESC"
			);
			$rscount=true;
			$data=M("mod_flk_video")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$data[$k]=$v;
				}
			} 
			$per_page=$start+$limit;
	 		$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goAssign(array(
				"list"=>$data,
				 "per_page"=>$per_page
			));
			$this->smarty->display("flk_video/index.html");
		}
		
		
		public function onIframe(){
			$where=" status<11 AND shopid=".SHOPID;
			$url="/shopadmin.php?m=flk_video";
			$option=array(
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_flk_video")->select($option,$rscount);
			 
			$this->smarty->goAssign(array(
				"data"=>$data,
				 
			));
			$this->smarty->display("flk_video/iframe.html");
		}
		
		public function onAdd(){
			$_SESSION['ssupload']=true;
			$id=get_post('id','i');
			if($id){
				$data=M("mod_flk_video")->selectRow("id=".$id);
				$data["trueimgurl"]=images_site($data["imgurl"]);
			}
			 
			$this->smarty->goAssign(array(
				"data"=>$data
				 
			));
			$this->smarty->display("flk_video/add.html");
		}
		
		public function onSave(){
			$id=get_post('id','i');
			$data=M("mod_flk_video")->postData();
			if($id){
				$row=M("mod_flk_video")->selectRow("id=".$id);
				if($row['shopid']!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				unset($data['url']);
				M("mod_flk_video")->update($data,"id=".$id);
			}else{
				$data['shopid']=SHOPID;
				$id=M("mod_flk_video")->insert($data);
			}
			$redata=array(
				"id"=>$id
			);
			$this->goAll("保存成功",0,$redata);
		}
		
		public function onStatus(){
			$_GET['ajax']=1;
			$status=get('status','i');
			$id=get('id','i');
			$row=M("mod_flk_video")->selectRow("id=".$id);
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row['status']==2){
				$status=0;
			}else{
				$status=2;
			}
			M("mod_flk_video")->update(array(
				"status"=>$status
			),"id=".$id);
			$rdata=array(
				"status"=>$status
			);
			$this->goAll("success",0,$rdata);
		}
		
		public function onDelete(){
			$_GET['ajax']=1;
			$id=get_post('id','i');
			$status=11;
			$row=M("mod_flk_video")->selectRow("id=".$id);
			 
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_flk_video")->update(array("status"=>$status),"id=".$id);
			$this->goAll("删除成功");
		}
	}
?>