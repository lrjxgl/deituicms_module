<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsbuyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
		
			$url="/module.php?m=fsbuy&a=default";
			$limit=24;
			$start=get("per_page","i");
			$type=get("type","h");
			switch($type){
				case "done":
					$where=" status=3 ";
					break;
				case "begin":
					$where=" status=1 ";
					break;
				case "doing":
					$where=" status=2 ";
					break;
				default:
						$where=" status in(1,2,3) ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" fsid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsbuy")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$seo=M("seo")->get("fsbuy");
			$this->smarty->goassign(
				array(
					"seo"=>$seo,
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("fsbuy/index.html");
		}
		
		public function onList(){
			$where=" status=2 ";
			$url="/module.php?m=fsbuy&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" fsid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsbuy")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("fsbuy/list.html");
		}
		
		public function onShow(){
			$fsid=get_post("fsid","i");
			$discount=100;
			
			$data=M("mod_fsbuy")->selectRow(array("where"=>"fsid={$fsid}"));
			$data['imgurl']=images_site($data['imgurl']);
			if($data["mp4url"]){
				$data["mp4url"]=images_site($data["mp4url"]);
			}
			$data["havenum"]=$data["maxnum"]-$data["buynum"];
			$data["stime"]=date("m-d H:i:s",strtotime($data["startTime"]));
			if($data["fstype"]==2){
				$step_config=MM("fsbuy","fsbuy")->parseStepConfig($data["step_config"],$data["buynum"]);
				$discount=MM("fsbuy","fsbuy")->getStepConfigDiscount($step_config);
			}
			
			 
			$userid=M("login")->userid;
			if($userid){
				$addr=M("user_lastaddr")->get($userid);
				$user=M("user")->selectRow(array(
					"where"=>" userid=".$userid,
					"fields"=>" userid,nickname,money"
				));
				$order=M("mod_fsbuy_order")->selectRow("userid=".$userid." AND fsid=".$fsid);
			}
			//产品规格
			$ksList=M("mod_fsbuy_ks")->select(array(
				"where"=>"fsid=".$fsid
			));
			$seo=array(
				"title"=>$data["title"],
				"description"=>$data["description"]
			);
			$this->smarty->assign(array(
				"seo"=>$seo
			));
			$fsconfig=M("mod_fsbuy_config")->selectRow("1");
			$this->addView($fsid,$userid);
			//邀请推广
			$invite_fsuserid=get("invite_fsuserid","i");
			if($invite_fsuserid){
				$_SESSION["ss_invite_fsuserid"]=$invite_fsuserid;
				setcookie("ck_invite_fsuserid",$invite_fsuserid,time()+3600*24,"/",DOMAIN);
			}elseif(isset($_COOKIE["ck_invite_fsuserid"])){
				$_SESSION["ss_invite_fsuserid"]=intval($_COOKIE["ck_invite_fsuserid"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"need_num"=>$data["minnum"]-$data["buynum"],
				"step_config"=>$step_config,
				"discount"=>$discount,
				 
				"user"=>$user,
				"order"=>$order,
				"ksList"=>$ksList,
				"addr"=>$addr,
				"fsconfig"=>$fsconfig,
				"shareurl"=>HTTP_HOST."/module.php?m=fsbuy&a=show&fsid=".$fsid."&invite_fsuserid=".$userid
			));
			$this->smarty->display("fsbuy/show.html");
		}
		public function onOrderList(){
			$fsid=get("fsid","i");
			$list=M("mod_fsbuy_order")->select(array(
				"where"=>" fsid=".$fsid." AND ispay=1 AND status<4 ",
				"order"=>"orderid DESC"
				
			));
			
			if(!empty($list)){
				foreach($list as $k=>$v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids,"userid,user_head,nickname");
				 
				foreach($list as $k=>$v){
					if(!isset($us[$v["userid"]])){
						unset($list[$k]);
						continue;
					}
					$v=$us[$v["userid"]];
					$list[$k]=$v;
				}
				
			}else{
				$list=[];
			}
			echo json_encode(array(
				"error"=>0,
				"message"=>"success",
				"data"=>array(
					"list"=>$list
				)
			));
		}
		 
		public function oninviteph(){
			$fsid=get("fsid","i");
			$fsbuy=M("mod_fsbuy")->selectRow("fsid=".$fsid);
			$sql="select count(*) as ct,invite_fsuserid from ".table('mod_fsbuy_order')."  
					where fsid=".$fsid." AND ispay=1 AND invite_fsuserid>0 
					group by invite_fsuserid 
					order by ct 
			";
			$list=M("mod_fsbuy_order")->getAll($sql);
			 
			if($list){
				foreach($list as $k=>$v){
					$uids[]=$v["invite_fsuserid"];
				}
				$us=M("user")->getUserByIds($uids,"userid,user_head,nickname");
				 
				foreach($list as $k=>$v){
					if(!isset($us[$v["invite_fsuserid"]])){
						unset($list[$k]);
						continue;
					}
					$p=$us[$v["invite_fsuserid"]];
					$p["num"]=$v["ct"];
					$p["money"]=$p["num"]*$fsbuy["invite_money"];
					$list[$k]=$p;
				}
			}
			echo json_encode(array(
				"error"=>0,
				"message"=>"success",
				"data"=>array(
					"list"=>$list
				)
			));
		} 
		public function onAddr(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$addr=M("user_lastaddr")->get($userid);
			$this->smarty->goAssign(array(
				"addr"=>$addr
			));
			
		}
		
		public function addView($fsid,$userid){
			$row=M("mod_fsbuy_view")->selectRow("fsid=".$fsid." AND userid=".$userid);
			if(!$row){
				M("mod_fsbuy_view")->insert(array(
					"fsid"=>$fsid,
					"userid"=>$userid,
					"dateline"=>time()
					
				));
				M("mod_fsbuy")->changenum("viewnum",1,"fsid=".$fsid);
			}
		} 
		
	}

?>