<?php
	if(!defined("ROOT_PATH")) exit("die Access ");
	class checkinControl extends skymvc{
		public $userid;
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			$this->userid=M("login")->userid;
		}
		
		public function onDefault(){
			$where=" status in(0,1) ";
			$url="/module.php?m=checkin&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("checkin","checkin")->select($option,$rscount);
			$moodList=MM("checkin","checkin_config")->moodList();
			if($data){
				$uids=[];
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$u=$us[$v["userid"]];
					$v['nickname']=$u['nickname'];
					$v['user_head']=$u['user_head'];
					 
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			
			 
			$checkConfig=MM("checkin","checkin_config")->get();
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"moodList"=>$moodList,
					"url"=>$url,
					"checkConfig"=>$checkConfig
				)
			);
			$this->smarty->display("checkin/index.html");
		}

		public function onMy(){
			M("login")->checkLogin();
			$where=" userid=".$this->userid;
			$url="/module.php?m=checkin&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("checkin","checkin")->select($option,$rscount);
			 
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$checkConfig=MM("checkin","checkin_config")->get();
			$moodList=MM("checkin","checkin_config")->moodList();
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"moodList"=>$moodList,
					"url"=>$url,
					"checkConfig"=>$checkConfig
				)
			);
			$this->smarty->display("checkin/my.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			if($id){
				$data=MM("checkin","checkin")->selectRow("id={$id}");
				
			}
			$this->smarty->assign(array(
				"data"=>$data
			));
			$this->smarty->display("checkin/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			if($plan_id){
				$data=MM("checkin","checkin")->selectRow("id={$id}");
				
			}
			$this->smarty->assign(array(
				"data"=>$data
			));
			$this->smarty->display("checkin/add.html");
		}
		
		public function onGet(){
			$row=MM("checkin","checkin_index")->selectRow(array("where"=>"userid=".$this->userid." AND type_id=1" ));
			$this->loadConfig("gold_grade");
			$c_gold=$this->config_item("gold");
			$gold=min($c_gold['checkin']+(isset($row['gold'])?$row['gold']:0),$c_gold['checkin_max']);
			$day=date("Ymd");
			$ischecked=0;
			$days=0;
			if($row && $row['last_day']==$day){
				$ischecked=1;
			}
			if(!empty($row) && ($row['last_day']==date("Ymd",strtotime("-1 day")) || $row['last_day']==$day )){
				$days=$row['days'];
			}
			$rdata=array(
				"ischecked"=>$ischecked,
				"gold"=>$gold,
				"days"=>$days
			);
			$this->goAll("success",0,$rdata);
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			M("login")->checkLogin();
			$data["userid"]=$userid;
			$data["day"]=$day=date("Ymd");
			$data["dateline"]=time();
			$data["mood"]=post("mood",'i');
			$data["content"]=post("content",'h');
			if(empty($data["content"])){
				$data["content"]="啥也不想说";
			}
			$data["type_id"]=1;
			$data["ip"]=ip();
			if(MM("checkin","checkin")->selectRow("userid=".$userid." AND day=".$day." ")){
				$this->goall("今天你已经签到过了",1);
			}
			
			
			//签到积分 金币 处理
			$config=MM("checkin","checkin_config")->get();
		 
			 
			
			$row=MM("checkin","checkin_index")
				->selectRow("userid=".$this->userid." AND type_id=1" );
			//判断是否间断
			if(!empty($row) && $row['last_day']!=date("Ymd",strtotime("-1 day"))){
				$row['grade']=0;
				$row['days']=0; 
				$row['gold']=0;
			}
			$days=isset($row['days'])?$row['days']+1:1;
			$all_times=isset($row['all_times'])?($row['all_times']+1):1;
			$gold=min($config["gold"]+$config["day_gold_p"]*($days-1),$config['max_gold']);
			$grade=min($config["grade"]+$config["day_grade_p"]*($days-1),$config['max_grade']);
			$data["gold"]=$gold;
			$data["grade"]=$grade;
			MM("checkin","checkin")->insert($data);
			$in_data=array(
				"type_id"=>1,
				"userid"=>$userid,
				"dateline"=>time(),
				"grade"=>$grade,
				"gold"=>$gold,
				"last_day"=>$day,
				"last_ip"=>ip(),
				"all_times"=>$all_times,
				"is_valid"=>1,
				"days"=>$days
			);
			if($row){ 
				 MM("checkin","checkin_index")->update($in_data,"id=".$row['id']);
			}else{
				MM("checkin","checkin_index")->insert($in_data);
			}
			//处理金币
			M("user")->addMoney(array(
				"gold"=>$gold,
				"userid"=>$userid,
				"content"=>"恭喜你连续签到".$days."次，本次获得了".$gold."个金币,"
				 
			));
	 		 
						 
			//处理积分
			M("user")->addMoney(array(
				"grade"=>$grade,
				"userid"=>$userid,
				"content"=>"恭喜你连续签到".$days."次，本次获得了".$grade."个积分，"
				 
			));	
			$this->goall("签到成功");
		}
		
		
	}

?>