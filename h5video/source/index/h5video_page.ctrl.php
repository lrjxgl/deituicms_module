<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class h5video_pageControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=h5video_page&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" pageid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_h5video_page")->select($option,$rscount);
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
			$this->smarty->display("h5video_page/index.html");
		}
		public function onItems(){
			$pageid=get_post("pageid","i");
			$rscount=true;
			$option=array(
				"where"=>" pageid=".$pageid." AND status=1 ",
				"order"=>"orderindex ASC"
			);
			$items=M("mod_h5video_page_item")->select($option,$rscount);
			if($items){
				foreach($items as $k=>$v){
					$styleids[]=$v["styleid"];
				}
				$styles=MM("h5video","h5video_style")->getListByIds($styleids);
				foreach($items as $k=>$v){
					$v["imgurl"] && $v["trueimgurl"]=images_site($v["imgurl"]);
					$v["styleid"] && $v["style_title"]=$styles[$v["styleid"]]["title"];
					$items[$k]=$v;
				}
			}
			$page=M("mod_h5video_page")->selectRow(array("where"=>"pageid=".$pageid));
			$this->smarty->goAssign(array(
				"items"=>$items,
				"page"=>$page
			));
		}
		public function onAdd(){
			$pageid=get_post("pageid","i");
			if($pageid){
				$data=M("mod_h5video_page")->selectRow(array("where"=>"pageid=".$pageid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("h5video_page/add.html");
		}
		
		public function onSave(){
			$pageid=get_post("pageid","i");
			$data=M("mod_h5video_page")->postData();
			$data["status"]=1;
			if($pageid){
				M("mod_h5video_page")->update($data,"pageid='$pageid'");
			}else{
				M("mod_h5video_page")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$pageid=get_post('pageid',"i");
			$status=get_post("status","i");
			M("mod_h5video_page")->update(array("status"=>$status),"pageid=$pageid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$pageid=get_post('pageid',"i");
			M("mod_h5video_page")->update(array("status"=>11),"pageid=$pageid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>