<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class im_giftControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=im_gift&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" giftid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_im_gift")->select($option,$rscount);
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
			$this->smarty->display("im_gift/index.html");
		}
		
		public function onAdd(){
			$giftid=get_post("giftid","i");
			if($giftid){
				$data=M("mod_im_gift")->selectRow(array("where"=>"giftid=".$giftid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("im_gift/add.html");
		}
		
		public function onSave(){
			$giftid=get_post("giftid","i");
			$data=M("mod_im_gift")->postData();
			if($giftid){
				M("mod_im_gift")->update($data,"giftid='$giftid'");
			}else{
				M("mod_im_gift")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$giftid=get_post('giftid',"i");
			$row=M("mod_im_gift")->selectRow("giftid=".$giftid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_im_gift")->update(array("status"=>$status),"giftid=$giftid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onRecommend(){
			$giftid=get_post('giftid',"i");
			$row=M("mod_im_gift")->selectRow("giftid=".$giftid);
			if($row["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_im_gift")->update(array("isrecommend"=>$status),"giftid=$giftid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$giftid=get_post('giftid',"i");
			M("mod_im_gift")->update(array("status"=>11),"giftid=$giftid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>