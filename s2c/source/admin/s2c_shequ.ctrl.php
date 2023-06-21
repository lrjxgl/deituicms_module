<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class s2c_shequControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=s2c_shequ&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" scid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_s2c_shequ")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$teamids[]=$v["teamid"];
				}
				$teams=MM("s2c","s2c_team")->getListByIds($teamids);
				foreach($data as $k=>$v){
					$v["team_nickname"]=$teams[$v["teamid"]]["nickname"];
					$v["imgurl"]=images_site($v["imgurl"]);
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
					"url"=>$url
				)
			);
			$this->smarty->display("s2c_shequ/index.html");
		}
		
		public function onAdd(){
			$scid=get_post("scid","i");
			if($scid){
				$data=M("mod_s2c_shequ")->selectRow(array("where"=>"scid=".$scid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("s2c_shequ/add.html");
		}
		
		public function onSave(){
			$scid=get_post("scid","i");
			$data=M("mod_s2c_shequ")->postData();
			if($scid){
				M("mod_s2c_shequ")->update($data,"scid='$scid'");
			}else{
				M("mod_s2c_shequ")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$scid=get_post('scid',"i");
			$row=M("mod_s2c_shequ")->selectRow("scid=".$scid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_s2c_shequ")->update(array(
				"status"=>$status
			),"scid=".$scid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$scid=get_post('scid',"i");
			M("mod_s2c_shequ")->update(array("status"=>11),"scid=$scid");
			$this->goAll("删除成功");
			 
		}
		
		public function onBind(){
			$scid=get_post('scid',"i");
			$row=M("mod_s2c_shequ")->selectRow("scid=".$scid);
			if($row["teamid"]>0){
				$this->goAll("请先下解绑团长",1);
			}
			$teamList=M("mod_s2c_team")->select(array(
				"where"=>" scid=0 AND status=1 "
			));
			$this->smarty->goAssign(array(
				"shequ"=>$row,
				"teamList"=>$teamList
			));
			$this->smarty->display("s2c_shequ/bind.html");
		}
		public function onBindSave(){
			$scid=get_post('scid',"i");
			$teamid=get_post("teamid","i");
			
			$shequ=M("mod_s2c_shequ")->selectRow("scid=".$scid);
			
			$team=M("mod_s2c_team")->selectRow("teamid=".$teamid);
			if(!$team || !$shequ){
				$this->goAll("数据出错",1);
			}
			if($shequ["teamid"]){
				$this->goAll("当前社区已经绑定团长，请先解绑",1);
			}
			if($team["scid"]){
				$this->goAll("当前团长已经绑定社区，请先解绑",1);
			}
			
			M("mod_s2c_team")->begin();
			M("mod_s2c_shequ")->update(array(
				"teamid"=>$teamid
			),"scid=".$scid);
			M("mod_s2c_team")->update(array(
				"scid"=>$scid
			),"teamid=".$teamid);
			M("mod_s2c_team")->commit();
			
			$this->goAll("绑定成功");
		}
		
		public function onUnBind(){
			$scid=get_post('scid',"i");
			$row=M("mod_s2c_shequ")->selectRow("scid=".$scid);
			if($row["status"]==1){
				$this->goAll("请先下线社区",1);
			}
			M("mod_s2c_team")->begin();
			M("mod_s2c_shequ")->update(array(
				"teamid"=>0
			),"scid=".$scid);
			M("mod_s2c_team")->update(array(
				"scid"=>0
			),"teamid=".$row["teamid"]);
			M("mod_s2c_team")->commit();
			$this->goAll("解绑成功");
		}
		 
	}

?>