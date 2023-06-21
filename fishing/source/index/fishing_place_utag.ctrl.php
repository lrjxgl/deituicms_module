<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_place_utagControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fishing_place_utag&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_place_utag")->select($option,$rscount);
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
			$this->smarty->display("fishing_place_utag/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fishing_place_utag&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_place_utag")->select($option,$rscount);
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
			$this->smarty->display("fishing_place_utag/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_fishing_place_utag")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fishing_place_utag/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$placeid=post("placeid","i");
			//钓点
			$place=M("mod_fishing_place")->selectRow("placeid=".$placeid);
			if(!$place || $place["status"]>1){
				$this->goAll("钓点已下线",1);
			}
			$con=post("content","x");
			$con=str_replace("\r\n","\n",$con);
			$arr=explode("\n",$con);
			$tags=[];
			$utag=M("mod_fishing_place_utag")->where("placeid=".$placeid." AND userid=".$userid." ")->row();
			if(!empty($utag)){
				$b2=explode("\n",$utag["content"]);
				$newTags=array_diff($arr,$b2);
				$c1=array_merge($arr,$b2);
				$all=implode("\n",$c1);
				M("mod_fishing_place_utag")->update(array(
					"content"=>$all
				),"id=".$utag["id"]);
			}else{
				$newTags=$arr;
				M("mod_fishing_place_utag")->insert(array(
					"content"=>$con,
					"userid"=>$userid,
					"placeid"=>$placeid,
					"createtime"=>date("Y-m-d H:i:s")
				));
			} 
			foreach($newTags as $title){
				if(!empty($title)){
					$title=trim($title);
					$tag=M("mod_fishing_place_tag")->selectRow("placeid=".$placeid." AND title='".sql($title)."' ");
					if($tag){
						M("mod_fishing_place_tag")->update(array(
							"grade"=>$tag["grade"]+1
						),"id=".$tag["id"]);
					}else{
						M("mod_fishing_place_tag")->insert(array(
							"placeid"=>$placeid,
							"title"=>sql($title),
							"createtime"=>date("Y-m-d H:i:s")
						));
					}
				}
			}
			//更新地点tag
			$tags=M("mod_fishing_place_tag")->selectCols(array(
				"where"=>"placeid=".$placeid." AND status in(0,1)",
				"order"=>"grade DESC",
				"limit"=>5,
				"fields"=>"title"
			));
			$tagcon="";
			if(!empty($tags)){
				$tagcon=implode(" ",$tags);
			}
			$config=M("mod_fishing_config")->selectRow("1");
			M("mod_fishing_place")->update(array(
				"tags"=>$tagcon,
				"grade"=>$place["grade"]+$config["tag_post_grade"],
			),"placeid=".$placeid);
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_fishing_place_utag")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_fishing_place_utag")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>