<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class ttcj_joinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$cjid=get_post("cjid","id");
			$where=" cjid=".$cjid;
			$url="/module.php?m=ttcj_join&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ttcj_join")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];				
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v['user_head']=$us[$v['userid']]['user_head'];
					$v['nickname']=$us[$v['userid']]['nickname'];
					$data[$k]=$v;
				}
				
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("ttcj_join/index.html");
		}
		
		public function onJoin(){
			M("login")->checkLogin();
			$cjid=get_post("cjid","id");
			$ttcj=M("mod_ttcj")->selectRow("cjid=".$cjid);
			if($ttcj['status']!=2 || strtotime($ttcj['endtime'])<time()){
				$this->goAll("该活动已经结束了",1);
			} 
			$userid=M("login")->userid;
			$address=post('address','h');
			$nickname=post('nickname','h');
			$telephone=post('telephone','h');
			if(empty($address) || empty($nickname) || empty($telephone)){
				$this->goAll("请确认联系方式",1);
			}
			M("user_lastaddr")->add(array(
				"address"=>$address,
				"nickname"=>$nickname,
				"telephone"=>$telephone
				
			),$userid);
			$row=M("mod_ttcj_join")->selectRow("cjid=".$cjid." AND userid=".$userid); 
			if($row){
				$this->goAll("你已经参与过了",1);
			}
			M("mod_ttcj_join")->insert(array(
				"cjid"=>$cjid,
				"userid"=>$userid,
				"createtime"=>date("Y-m-d H:i:s"),
				"nickname"=>$nickname,
				"address"=>$address,
				"telephone"=>$telephone, 
			));
			$join_num=M("mod_ttcj_join")->selectOne(array(
				"where"=>" cjid=".$cjid,
				"fields"=>" count(*) as ct"
			));
			M("mod_ttcj")->update(array(
				"join_num"=>$join_num
			),"cjid=".$cjid);
			$this->goAll("参与成功");
		}
		public function onMy(){
			M("login")->userid;
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=ttcj_join&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ttcj_join")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$ids[]=$v['cjid'];
				}
				$cjs=MM("ttcj","ttcj")->getListByIds($ids);
				foreach($data as $k=>$v){
					$v['title']=$cjs[$v['cjid']]['title'];
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
			$this->smarty->display("ttcj_join/my.html");
		}
		
	}

?>