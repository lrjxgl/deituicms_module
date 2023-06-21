<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_liveControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/moduleadmin.php?m=zbtao_live&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" liveid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_live")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_live/index.html");
		}
		
		public function onTag(){
			$tagid=get("tagid","i");
			$tag=M("mod_zbtao_tag")->selectRow("tagid=".$tagid." AND status=1 ");
			if(empty($tag)){
				$this->goAll("数据出错",1);
			}
			$start=get("per_page","i");
			$limit=12;
			$sql=" select p.liveid,p.followed_num,p.title,p.imgurl  
				from ".table('mod_zbtao_live_tag')." as t 
				left join ".table('mod_zbtao_live')." as p 
				on t.liveid=p.liveid 
				where t.tagid=".$tagid." AND p.status=1 
				limit $start,$limit 
			";
			$sql2=" select count(*) as ct
				from ".table('mod_zbtao_live_tag')." as t 
				left join ".table('mod_zbtao_live')." as p 
				on t.liveid=p.liveid 
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
			$this->smarty->display("zbtao_live/tag.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/moduleadmin.php?m=zbtao_live&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" liveid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_live")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_live/index.html");
		}
		
		public function onShow(){
			$liveid=get_post("liveid","i");
			$data=M("mod_zbtao_live")->selectRow(array("where"=>"liveid=".$liveid));
			if(empty($data) || $data["status"]>1){
				$this->goAll("主播已关停",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			//主播
			$pp=MM("zbtao","zbtao_pp")->get($data["ppid"]);
			//直播平台
			$ptids=explode(",",$data["zbpts"]);
			$pts=MM("zbtao","zbtao_pp_pts")->Dselect(array(
				"where"=>" ptid in("._implode($ptids).") AND status in(0,1) "
			));
			//带货记录
			$proList=MM("zbtao","zbtao_live_product")->Dselect(array(
				"where"=>" liveid=".$data["liveid"]." AND status=1 "
			));
			//用户关注
			$data["isFollow"]=0;
			$userid=M("login")->userid;
			$pp["isFollow"]=0;
			if($userid){
				$rs=M("mod_zbtao_live_follow")->selectRow("userid=".$userid." AND liveid=".$liveid);
				if($rs){
					$data["isFollow"]=1;
				}else{
					$data["isFollow"]=0;
				}
				$rs2=M("mod_zbtao_pp_follow")->selectRow("userid=".$userid." AND ppid=".$data["ppid"]);
				if($rs2){
					$pp["isFollow"]=1;
				}
				//带货关注情况
				if(!empty($proList)){
					foreach($proList as $k=>$p){
						$pids[]=$p["productid"];
					}
					$fps=M("mod_zbtao_live_product_follow")->selectCols(array(
						"fields"=>"productid",
						"where"=>" userid=".$userid." AND productid in("._implode($pids).") "
					));
					if(empty($fps)){
						$fps=[0];
					}
					foreach($proList as $k=>$p){
						if(in_array($p["productid"],$fps)){
							$p["isFollow"]=1;
						}else{
							$p["isFollow"]=0;
						}
						$proList[$k]=$p;
					}
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"pp"=>$pp,
				"proList"=>$proList,
				"pts"=>$pts
			));
			$this->smarty->display("zbtao_live/show.html");
		}
		public function onAdd(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$ppid=$pp["ppid"];
			$liveid=get_post("liveid","i");
			$pts=[];
			if($liveid){
				$data=M("mod_zbtao_live")->selectRow(array("where"=>"liveid=".$liveid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
				$pts=explode(",",$data["zbpts"]);
			}
			$ptcomList=MM("zbtao","zbtao_pp_pts")->ptcomList();
			$ptList=MM("zbtao","zbtao_pp_pts")->Dselect(array(
				"where"=>"ppid=".$ppid." AND status in(0,1) "
			));
			if( !empty($ptList)){
				
				foreach($ptList as $k=>$v){
					if($liveid && in_array($v["ptid"],$pts)){
						$v["selected"]=1;
					}else{
						$v["selected"]=0;
					}
					$ptList[$k]=$v;
				}
			} 
			$this->smarty->goassign(array(
				"data"=>$data,
				"ptList"=>$ptList
			));
			$this->smarty->display("zbtao_live/add.html");
		}
		public function onMy(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			 
			$ppid=$pp["ppid"];
			$where=" ppid=".$ppid." AND status in(0,1,2) ";
			$url="/moduleadmin.php?m=zbtao_live&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" liveid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_live")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_live/my.html");
		}
		public function onSave(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$liveid=get_post("liveid","i");
			$data=M("mod_zbtao_live")->postData();
			$pts=explode(",",$data["zbpts"]);
			foreach($pts as $k=>$pt){
				$pts[$k]=intval($pt);
			}
			$data["zbpts"]=implode(",",$pts);
			if($liveid){
				$row=M("mod_zbtao_live")->selectRow("liveid=".$liveid);
				if($row["ppid"]!=$pp["ppid"]){
					$this->goAll("暂无权限",1);
				}
				M("mod_zbtao_live")->update($data,"liveid=".$liveid);
			}else{
				$data["ppid"]=$pp["ppid"];
				M("mod_zbtao_live")->insert($data);
			}
			
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$liveid=get_post('liveid',"i");
			$row=M("mod_zbtao_live")->selectRow("liveid=".$liveid);
			if($row["ppid"]!=$pp["ppid"]){
				$this->goAll("暂无权限",1);
			}
			$status=get_post("status","i");
			M("mod_zbtao_live")->update(array("status"=>$status),"liveid=$liveid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$liveid=get_post('liveid',"i");
			$row=M("mod_zbtao_live")->selectRow("liveid=".$liveid);
			if($row["ppid"]!=$pp["ppid"]){
				$this->goAll("暂无权限",1);
			}
			M("mod_zbtao_live")->update(array("status"=>11),"liveid=$liveid");
			$this->goAll("删除成功");
			 
		}
		
		public function onFollowToggle(){
			M("login")->checkLogin();
			$liveid=get_post("liveid","i");
			$userid=M("login")->userid;
			$rs=M("mod_zbtao_live_follow")->selectRow("userid=".$userid." AND liveid=".$liveid);
			$isFollow=0;
			if($rs){
				M("mod_zbtao_live_follow")->delete("userid=".$userid." AND liveid=".$liveid);
				M("mod_zbtao_live")->changenum("followed_num",-1,"liveid=".$liveid);
			}else{
				M("mod_zbtao_live_follow")->insert(array(
					"userid"=>$userid,
					"liveid"=>$liveid
				));
				$isFollow=1;
				M("mod_zbtao_live")->changenum("followed_num",1,"liveid=".$liveid);
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
				"fields"=>"liveid"
			);
			$liveids=M("mod_zbtao_live_follow")->selectCols($ops);
			$list=[];
			if(!empty($liveids)){
				$list=MM("zbtao","zbtao_live")->Dselect(array(
					"where"=>" liveid in("._implode($liveids).") AND status=1 "
				));
			}
			
			$this->smarty->goAssign(array(
				"list"=>$list
			));
			$this->smarty->display("zbtao_live/myfollow.html");
		}
		
	}

?>