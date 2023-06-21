<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_resourceControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=house_resource&a=default";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND description like '%".$keyword."%' ";
			}
			 
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			if($type){
				$url.="&type=".$type;
				switch($type){
					case "new":
						$where.=" AND isnew=1 ";
						break;
					case "ershou":
						$where.=" AND isnew=0 ";
						break;
				}
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("house","house_resource")->Dselect($option,$rscount);
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
			$this->smarty->display("house_resource/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=house_resource&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("house","house_resource")->Dselect($option,$rscount);
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
			$this->smarty->display("house_resource/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_house_resource")->selectRow(array("where"=>"id=".$id));
			if(!$data || $data["status"]>3){
				$this->goAll("当前信息已删除",1,0,"/");
			}
			if($data["sc_id"]>0){
				$data["sc_id_title"]=M("site_city")->selectOne(array(
					"where"=>" sc_id=".$data["sc_id"],
					"fields"=>"title"
				));
			}
			$imgslist=array();
			if($data["imgsdata"]){
				$imgs=explode(",",$data['imgsdata']);
				foreach($imgs as $img){
					$imgslist[]=images_site($img);
				}
			}
			$author=M("user")->getUser($data["userid"]);
			$agent=MM("house","house_agent")->selectRow(array(
				"where"=>"userid=".$data["userid"]." AND status=1",
				"fields"=>"id,userid,uhead,truename,telephone,description"
			));
			if($agent){
				$agent["uhead"]=images_site($agent["uhead"]);
				$agent["house_num"]=MM("house","house_resource")->where("userid=".$data["userid"])->count();
				$agent["tuan_num"]=MM("house","house_tuan")->where("userid=".$data["userid"])->count();
			}
			$userid=M("login")->userid;
			$isFav=0;
			if($userid){
				$row=M("mod_house_resource_love")->selectRow("userid=".$userid." AND resid=".$id);
				if($row){
					$isFav=1;
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgslist"=>$imgslist,
				"author"=>$author,
				"agent"=>$agent,
				"isFav"=>$isFav
			));
			$this->smarty->display("house_resource/show.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/module.php?m=house_resource&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("house","house_resource")->Dselect($option,$rscount);
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
			$this->smarty->display("house_resource/my.html");
		}
		
		public function onAdd(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			$userid=M("login")->userid;
			$agent=M("mod_house_agent")->selectRow(array(
				'where'=>"userid=".$userid." AND status=1",
				"fields"=>"userid,status"
			));
			if(empty($agent)){
				$this->goAll("您暂无权限发布房源",1);
			}
			$scList=M("site_city")->select(array(
				"where"=>" status=1 AND pid=0 ",
				"order"=>" orderindex ASC "
			));
			if($id){
				$data=M("mod_house_resource")->selectRow(array("where"=>"id=".$id));
				if($data["imgsdata"]){
						$imgs=explode(",",$data["imgsdata"]);
						foreach($imgs as $v){
							$imgsdata[]=array(
								"imgurl"=>$v,
								"trueimgurl"=>images_site($v)
							);
						}
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata,
				"scList"=>$scList
			));
			$this->smarty->display("house_resource/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			$data=M("mod_house_resource")->postData();
			$userid=M("login")->userid;
			$agent=M("mod_house_agent")->selectRow(array(
				'where'=>"userid=".$userid." AND status=1",
				"fields"=>"userid,status"
			));
			if(empty($agent)){
				$this->goAll("您暂无权限发布房源",1);
			}
			//处理imgsdata
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				foreach($ims as $im){
					if($im!="undefined" && $im!=""){
						$imgsdata[]=$im;
					}
				}
				if(!empty($imgsdata)){
					 
					$data["imgsdata"]=implode(",",$imgsdata);
				}
				
			} 
			$data["updatetime"]=date("Y-m-d H:i:s");
			if($id){
				M("mod_house_resource")->update($data,"id='$id'");
			}else{
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				
				$data["status"]=1;
				M("mod_house_resource")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_house_resource")->update(array(
				"status"=>$status,
				"updatetime"=>date("Y-m-d H:i:s")
			),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_house_resource")->update(array(
				"status"=>11,
				"updatetime"=>date("Y-m-d H:i:s")
			),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>