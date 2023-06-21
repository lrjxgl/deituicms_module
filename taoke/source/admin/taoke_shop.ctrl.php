<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class taoke_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) ";
			$url="/moduleadmin.php?m=taoke_shop&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_taoke_shop")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$data[$k]=$v;
				}
			}
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
			$this->smarty->display("taoke_shop/index.html");
		}
		
		public function onAdd(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$data=M("mod_taoke_shop")->selectRow(array("where"=>"shopid={$shopid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("taoke_shop/add.html");
		}
		
		public function onSave(){
			
			$shopid=get_post("shopid","i");

			$data=M("mod_taoke_shop")->postData();
			if($shopid){
				M("mod_taoke_shop")->update($data,"shopid='$shopid'");
			}else{
				M("mod_taoke_shop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$status=get_post("status","i");
			M("mod_taoke_shop")->update(array("status"=>$status),"shopid=$shopid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_taoke_shop")->update(array("status"=>11),"shopid=$shopid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>