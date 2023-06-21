<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_loupanControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=house_loupan";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_loupan")->select($option,$rscount);
			if($data){
				foreach($data as &$v){
					$v['imgurl']=images_site($v['imgurl']);
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("house_loupan/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=house_loupan&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_loupan")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("house_loupan/list.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_house_loupan")->selectRow(array("where"=>"id=".$id." AND status=1 "));
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$data['imgurl']=images_site($data['imgurl']);
			$data["videourl"]=images_site($data["videourl"]);
			$hxList=MM("house","house_huxing")->Dselect(array(
				"where"=>"lpid=".$id
			));
			$ptList=MM("house","house_peitao")->Dselect(array(
				"where"=>"lpid=".$id." AND status=1 "
			));
			$userid=M("login")->userid;
			$isFav=0;
			if($userid){
				$row=M("mod_house_loupan_love")->selectRow("userid=".$userid." AND lpid=".$id);
				if($row){
					$isFav=1;
				}
			} 
			$this->smarty->goassign(array(
				"data"=>$data,
				"hxList"=>$hxList,
				"ptList"=>$ptList,
				"isFav"=>$isFav
			));
			$this->smarty->display("house_loupan/show.html");
		}
		
		public function onAddClick(){
			
			$id=get("id",'i');
			M("mod_house_loupan")->changenum("view_num",1,"id=".$id);
			echo 1;
		}
		
	}

?>