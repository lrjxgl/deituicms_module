<?php
class h5videoControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		$where=" status in(0,1,2) AND isrecommend=1 ";
		$url="/module.php?m=h5video&a=default";
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
	
	public function onTemplate(){
		$where=" status in(0,1,2) AND istpl=1 ";
		$url="/module.php?m=h5video&a=default";
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
		$this->smarty->display("h5video/template.html");
		
	}
	public function onCopy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$vid=get("vid");
		$h5video=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));
		if(!$h5video["istpl"] && $h5video["userid"]!=$userid){
			$this->goAll("当前设计非模板无法复制",1);
		}
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
		$h5video["userid"]=$userid;
		unset($h5video["istpl"]);
		$newvid=M("mod_h5video")->insert($h5video);
		if($pages){
			foreach($pages as $page){
				$page["vid"]=$newvid;
				$its=$pageItems[$page["pageid"]];
				unset($page["pageid"]);
				$page["userid"]=$userid;
				$newpageid=M("mod_h5video_page")->insert($page);
				if(!empty($its)){
					foreach($its as $item){
						$item["vid"]=$newvid;
						$item["pageid"]=$newpageid;
						unset($item["id"]);
						$item["userid"]=$userid;
						M("mod_h5video_page_item")->insert($item);
					}
				}
			}
		}
		M("mod_h5video")->commit();
		$this->goAll("复制成功",0,$newvid);
	}
	public function onDesign(){
		M("login")->checkLogin();
		$vid=get_post("vid","i");
		$data=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));
		$animateList=M("mod_h5video_animate")->select();
		$styleList=M("mod_h5video_style")->select();
		$itypeList=MM("h5video","h5video_page_item")->itypeList();
		$this->smarty->goassign(array(
			"data"=>$data,
			"aniList"=>$animateList,
			"styleList"=>$styleList,
			"itypeList"=>$itypeList,
			"iswap"=>ISWAP
		));
		$this->smarty->display("h5video/design.html");
	}
	public function onData(){
		$vid=get_post("vid","i");
		$data=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));
		$pages=M("mod_h5video_page")->select(array(
			"where"=>"vid=".$vid,
			"order"=>" orderindex ASC"
		));
		$animateList=M("mod_h5video_animate")->select();
		$styleList=M("mod_h5video_style")->select();
		$itypeList=MM("h5video","h5video_page_item")->itypeList();
		$this->smarty->goassign(array(
			"data"=>$data,
			"pages"=>$pages,
			"aniList"=>$animateList,
			"styleList"=>$styleList,
			"itypeList"=>$itypeList
		));
	}
	
	public function onShow(){
		$vid=get("vid","i");
		$h5video=M("mod_h5video")->selectRow("vid=".$vid);
		$bgmp3="";
		if($h5video["bgmp3"]){
			$music=M("mod_h5video_music")->selectRow("musicid=".$h5video["bgmp3"]);
			$bgmp3=images_site($music["url"]);
		}
		$pages=M("mod_h5video_page")->select(array(
			"where"=>" status=1 AND vid=".$vid
		));
		$items=M("mod_h5video_page_item")->select(array(
			"where"=>"vid=".$vid." AND status=1 ",
			"order"=>"orderindex ASC,id ASC"
		));
		if($items){
			foreach($items as $k=>$v){
				$styleids[]=$v["styleid"];
				$pluginids[]=$v["pluginid"];
			}
			$styles=MM("h5video","h5video_style")->getListByIds($styleids);
			$plugins=MM("h5video","h5video_plugin")->getListByIds($pluginids);
			foreach($items as $it){
				$it["style"]=$styles[$it["styleid"]]["content"];
				$it["cssClass"]=$styles[$it["styleid"]]["cssClass"];
				$it["plugincontent"]=$plugins[$it["pluginid"]]["content"];
				if($it["w"]>0){
					$it["width"]="width:{$it['w']}px;";
				}
				if($it["h"]>0){
					$it["height"]="height:{$it['h']}px;";
				}
				if($it["imgurl"]){
					$it["imgurl"]=images_site($it["imgurl"]);
				}
				
				$pageItems[$it["pageid"]][]=$it;
			}
		}
		$videoData=array();
		if($pages){
			foreach($pages as $page){
				$page["items"]=$pageItems[$page["pageid"]];
				$allText="";
				if($page["items"]){
					foreach($page["items"] as $v){
						if($v["itype"]=='text'){
							$allText.=$v["content"]."。。。";
						}
						
					}
				}
				$page["allText"]=str_replace("\r\n","。。。",$allText);
				$videoData[]=$page;
			}
		}
		$pageid=get("pageid","i"); 
		$this->smarty->goAssign(array(
			"videoData"=>$videoData,
			"h5video"=>$h5video,
			"bgmp3"=>$bgmp3,
			"pageid"=>$pageid
		));
		$this->smarty->display("h5video/show.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" status in(0,1,2) AND userid=".$userid;
		$url="/module.php?m=h5video&a=my";
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
		$this->smarty->display("h5video/my.html");
		
	}
	public function onAdd(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$vid=get_post("vid","i");
		if($vid){
			$data=M("mod_h5video")->selectRow(array("where"=>"vid=".$vid));	
			$data=M("mod_h5video")->selectRow("vid=".$vid);
			if($data["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}			
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
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$vid=get_post("vid","i");
		$data=M("mod_h5video")->postData();
		if($vid){
			$row=M("mod_h5video")->selectRow("vid=".$vid);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_h5video")->update($data,"vid='$vid'");
		}else{
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			M("mod_h5video")->insert($data);
		}
		$this->goall("保存成功");
	} 
	
	public function onDelete(){
		$vid=get_post('vid',"i");
		M("mod_h5video")->update(array("status"=>11,"deltime"=>time()),"vid=$vid");
		$this->goAll("删除成功");
		 
	}
}

?>