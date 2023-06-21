<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gxny_shop_categoryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=gxny_shop_category&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gxny_shop_category")->select($option,$rscount);
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
			$this->smarty->display("gxny_shop_category/index.html");
		}
		
		public function onAdd(){
			$catid=get_post("catid","i");
			if($catid){
				$data=M("mod_gxny_shop_category")->selectRow(array("where"=>"catid=".$catid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gxny_shop_category/add.html");
		}
		
		public function onSave(){
			$catid=get_post("catid","i");
			$data=M("mod_gxny_shop_category")->postData();
			
			if($catid){
				M("mod_gxny_shop_category")->update($data,"catid='$catid'");
			}else{
				$data['shopid']=SHOPID;
				M("mod_gxny_shop_category")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$catid=get_post('catid',"i");
			$status=get_post("status","i");
			M("mod_gxny_shop_category")->update(array("status"=>$status),"catid=$catid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$catid=get_post('catid',"i");
			M("mod_gxny_shop_category")->update(array("status"=>11),"catid=$catid");
			$this->goAll("删除成功");
			 
		}
		
		public function onnewpro(){
			$catid=get("catid","i");
			$cat=M("mod_gxny_shop_category")->selectRow("catid=".$catid);
			$no=$cat["no"];
			$max=$no+600;
			$prefix=substr($cat["title"],0,1);
			M("mod_gxny_shop_category")->update(array(
				"no"=>$max
			),"catid=".$catid);
			for($i=$no;$i<$max;$i++){
				$nos=$prefix."m".$i;
				M("mod_gxny_shop_product")->insert(array(
					"shopid"=>SHOPID,
					"catid"=>$catid,
					"createtime"=>date("Y-m-d H:i:s"),
					"no"=>$nos,
					"orderindex"=>$i,
					"status"=>1
					
				));
			}
			$this->goAll("success");
		}
		
		
	}

?>