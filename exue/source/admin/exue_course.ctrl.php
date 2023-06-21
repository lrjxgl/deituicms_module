<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_courseControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=exue_course&a=default";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
				$url.="&keyword=".urlencode($keyword);
			}
			$stype=get("stype","i");
			if($stype){
				$where.=" AND stype=".$stype;
				$url.="&stype=".$stype;
			}
			$site_index=get("site_index","i");
			if($site_index){
				$where.=" AND site_index=".$site_index;
				$url.="&site_index=1";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" courseid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_course")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$cids[]=$v["catid"];
					$spids[]=$v["shopid"];
				}
				$sps=MM("exue","exue_shop")->getListByIds($spids);
				$cats=MM("exue","exue_category")->getListByIds($cids,"shopid,title");
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v["imgurl"]);
					$v["catid_name"]=$cats[$v["catid"]]["title"];
					$v["shop_title"]=$sps[$v["shopid"]]["title"];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"keyword"=>$keyword
				)
			);
			$this->smarty->display("exue_course/index.html");
		}
		
		public function onAdd(){
			$courseid=get_post("courseid","i");
			if($courseid){
				$data=M("mod_exue_course")->selectRow(array("where"=>"courseid=".$courseid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("exue_course/add.html");
		}
		
		public function onSave(){
			$courseid=get_post("courseid","i");
			$data=M("mod_exue_course")->postData();
			if($courseid){
				M("mod_exue_course")->update($data,"courseid='$courseid'");
			}else{
				M("mod_exue_course")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$courseid=get_post('courseid',"i");
			$row=M("mod_exue_course")->selectRow("courseid=".$courseid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_exue_course")->update(array(
				"status"=>$status
			),"courseid=".$courseid);
			$this->goall("状态修改成功");
		}
		public function onsite_index(){
			$courseid=get_post('courseid',"i");
			$row=M("mod_exue_course")->selectRow("courseid=".$courseid);
			$isrecommend=0;
			if($row["site_index"]==0){
				$isrecommend=1;
			}
			M("mod_exue_course")->update(array(
				"site_index"=>$isrecommend
			),"courseid=".$courseid);
			$this->goAll("success",0,$isrecommend);
		}
		public function onDelete(){
			$courseid=get_post('courseid',"i");
			M("mod_exue_course")->update(array("status"=>11),"courseid=$courseid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>