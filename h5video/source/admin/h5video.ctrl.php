<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class h5videoControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=h5video&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" vid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_h5video")->select($option,$rscount);
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
			$this->smarty->display("h5video/index.html");
		}
		
		public function onCopy(){
			$vid=get("vid");
			$h5video=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));
			unset($h5video["vid"]);
			
			$pages=M("mod_h5video_page")->select(array(
				"where"=>"vid=".$vid
			));
			$items=M("mod_h5video_page_item")->select(array(
				"where"=>"vid=".$vid
			));
			if($items){
				foreach($items as $v){
					$pageItems[$v["pageid"]][]=$v;
				}
			}
			
			//处理新数据
			M("mod_h5video")->begin();
			$h5video["title"].="-copy";
			$h5video["userid"]=0;
			unset($h5video["tpl"]);
			$newvid=M("mod_h5video")->insert($h5video);
			if($pages){
				foreach($pages as $page){
					$page["vid"]=$newvid;
					$its=$pageItems[$page["pageid"]];
					unset($page["pageid"]);
					$page["userid"]=0;
					$newpageid=M("mod_h5video_page")->insert($page);
					if(!empty($its)){
						foreach($its as $item){
							$item["vid"]=$newvid;
							$item["pageid"]=$newpageid;
							unset($item["id"]);
							$item["userid"]=0;
							M("mod_h5video_page_item")->insert($item);
						}
					}
				}
			}
			M("mod_h5video")->commit();
			$this->goAll("复制成功");
		}
		public function onShow(){
			$vid=get_post("vid","i");
			$data=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("h5video/show.html");
		}
		public function onDesign(){
			$vid=get_post("vid","i");
			$data=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));
			$animateList=M("mod_h5video_animate")->select();
			$styleList=M("mod_h5video_style")->select();
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"aniList"=>$animateList,
				"styleList"=>$styleList
			));
			$this->smarty->display("h5video/design.html");
		}
		public function onData(){
			$vid=get_post("vid","i");
			$data=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));
			$pages=M("mod_h5video_page")->select(array(
				"where"=>"status=1 AND vid=".$vid,
				"order"=>" orderindex ASC"
			));
			$animateList=M("mod_h5video_animate")->select();
			$styleList=M("mod_h5video_style")->select();
			$itypeList=MM("h5video","h5video_page_item")->itypeList();
			$pluginList=MM("h5video","h5video_plugin")->pluginList();
			$this->smarty->goassign(array(
				"data"=>$data,
				"pages"=>$pages,
				"aniList"=>$animateList,
				"styleList"=>$styleList,
				"itypeList"=>$itypeList,
				"pluginList"=>$pluginList
			));
		}
		public function onAdd(){
			$vid=get_post("vid","i");
			if($vid){
				$data=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));				
			}
			$musicList=M("mod_h5video_music")->select(array(
				"where"=>" status=1 "
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"musicList"=>$musicList
			));
			$this->smarty->display("h5video/add.html");
		}
		
		public function onSave(){
			$vid=get_post("vid","i");
			$data=M("mod_h5video")->postData();
			if($vid){
				M("mod_h5video")->update($data,"vid='$vid'");
			}else{
				M("mod_h5video")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$vid=get_post('vid',"i");
			$row=M("mod_h5video")->selectRow("vid=".$vid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_h5video")->update(array(
				"status"=>$status
			),"vid=".$vid);
			$this->goAll("success",0,$status);
		}
		public function onrecommend(){
			$vid=get_post('vid',"i");
			$row=M("mod_h5video")->selectRow("vid=".$vid);
			$status=1;
			if($row["isrecommend"]==1){
				$status=0;
			}
			M("mod_h5video")->update(array(
				"isrecommend"=>$status
			),"vid=".$vid);
			$this->goAll("success",0,$status);
		}
		public function onTpl(){
			$vid=get_post('vid',"i");
			$row=M("mod_h5video")->selectRow("vid=".$vid);
			$status=1;
			if($row["istpl"]==1){
				$status=0;
			}
			M("mod_h5video")->update(array(
				"istpl"=>$status
			),"vid=".$vid);
			$this->goAll("success",0,$status);
		}
		public function onDelete(){
			$vid=get_post('vid',"i");
			M("mod_h5video")->update(array("status"=>11,"deltime"=>time()),"vid=$vid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>