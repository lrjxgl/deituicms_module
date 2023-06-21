<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_ppControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status =1 ";
			$url="/moduleadmin.php?m=zbtao_pp&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ppid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_pp")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_pp/index.html");
		}
		
		public function onTag(){
			$tagid=get("tagid","i");
			$tag=M("mod_zbtao_tag")->selectRow("tagid=".$tagid." AND status=1 ");
			if(empty($tag)){
				$this->goAll("数据出错",1);
			}
			$start=get("per_page","i");
			$limit=12;
			$sql=" select p.ppid,p.followed_num,p.nickname,p.imgurl,p.gender 
				from ".table('mod_zbtao_pp_tag')." as t 
				left join ".table('mod_zbtao_pp')." as p 
				on t.ppid=p.ppid 
				where t.tagid=".$tagid." AND p.status=1 
				limit $start,$limit 
			";
			$sql2=" select count(*) as ct
				from ".table('mod_zbtao_pp_tag')." as t 
				left join ".table('mod_zbtao_pp')." as p 
				on t.ppid=p.ppid 
				where t.tagid=".$tagid." AND p.status=1 
				 
			"; 
			$list=M("mod_zbtao_pp_tag")->getAll($sql);
			if(!empty($list)){
				foreach($list as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$list[$k]=$v;
				}
			}
			$rscount=M("mod_zbtao_pp_tag")->getOne($sql2);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			 
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
			 
					"rscount"=>$rscount,
					"tag"=>$tag
				)
			);
			$this->smarty->display("zbtao_pp/tag.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/moduleadmin.php?m=zbtao_pp&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ppid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_pp")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_pp/index.html");
		}
		
		public function onShow(){
			$ppid=get_post("ppid","i");
			$data=M("mod_zbtao_pp")->selectRow(array("where"=>"ppid=".$ppid));
			if(empty($data) || $data["status"]>1){
				$this->goAll("主播已关停",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			//直播记录
			$zbList=MM("zbtao","zbtao_live")->Dselect(array(
				"where"=>" ppid=".$data["ppid"]." AND status=1 "
			));
			//带货记录
			$proList=MM("zbtao","zbtao_live_product")->Dselect(array(
				"where"=>" ppid=".$data["ppid"]." AND status=1 "
			));
			//用户关注
			$data["isFollow"]=0;
			$userid=M("login")->userid;
			if($userid){
				$rs=M("mod_zbtao_pp_follow")->selectRow("userid=".$userid." AND ppid=".$ppid);
				if($rs){
					$data["isFollow"]=1;
				}else{
					$data["isFollow"]=0;
				}
			}
			
			$this->smarty->goassign(array(
				"data"=>$data,
				"zbList"=>$zbList,
				"proList"=>$proList
			));
			$this->smarty->display("zbtao_pp/show.html");
		}
		
		
		public function onFollowToggle(){
			M("login")->checkLogin();
			$ppid=get_post("ppid","i");
			$userid=M("login")->userid;
			$rs=M("mod_zbtao_pp_follow")->selectRow("userid=".$userid." AND ppid=".$ppid);
			$isFollow=0;
			if($rs){
				M("mod_zbtao_pp_follow")->delete("userid=".$userid." AND ppid=".$ppid);
				M("mod_zbtao_pp")->changenum("followed_num",-1,"ppid=".$ppid);
			}else{
				M("mod_zbtao_pp_follow")->insert(array(
					"userid"=>$userid,
					"ppid"=>$ppid
				));
				M("mod_zbtao_pp")->changenum("followed_num",1,"ppid=".$ppid);
				$isFollow=1;
			}
			$this->goAll("success",0,array(
				"isFollow"=>$isFollow
			));
		}
		
		public function onMyFollow(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$ops=array(
				"where"=>" userid=".$userid,
				"fields"=>"ppid"
			);
			$ppids=M("mod_zbtao_pp_follow")->selectCols($ops);
			$list=[];
			if(!empty($ppids)){
				$list=MM("zbtao","zbtao_pp")->Dselect(array(
					"where"=>" ppid in("._implode($ppids).") AND status=1 "
				));
			}
			
			$this->smarty->goAssign(array(
				"list"=>$list
			));
			$this->smarty->display("zbtao_pp/myfollow.html");
		}
		
		public function onEdit(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$pp["trueimgurl"]=images_site($pp["imgurl"]);
			$this->smarty->goAssign(array(
				"data"=>$pp
			));
			$this->smarty->display("zbtao_pp/edit.html");
		}
		
		public function onSave(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$inarr=["imgurl","nickname","description"];
			$data=MM("zbtao","zbtao_pp")->postData();
			foreach($data as $k=>$v){
				if(!in_array($k,$inarr)){
					unset($data[$k]);
				}
			}
			MM("zbtao","zbtao_pp")->update($data,"ppid=".$pp["ppid"]);
			$this->goAll("保存成功");
		}
		
	}

?>