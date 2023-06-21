<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class party_joinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$pid=get("pid","i");
			$where=" pid=".$pid." AND status=1 ";
			$url="/module.php?m=party_join&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_party_join")->select($option,$rscount);
			if($data){
				$uids=[];
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					 
					$v["user_head"]=$us[$v["userid"]]["user_head"];
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
			$this->smarty->display("party_join/index.html");
		}
		 
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			
			$pid=get_post("pid","i");
			$party=M("mod_party")->selectRow(array(
				"where"=>"id=".$pid,
				"fields"=>"*"
			));
			if($party["status"]!=1){
				$this->goAll("活动未上线",1);
			}
			if($party["max_num"]<=$party["join_num"]){
				$this->goAll("活动参与人数已满",1);
			}
			if(strtotime($party["stime"])<time()){
				$this->goAll("活动已经开始了",1);
			}
			$row=M("mod_party_join")->selectRow("userid=".$userid." AND pid=".$pid);
			if($row){
				$this->goAll("你已经参加了",1);
			}
			$un=array("status");
			$data=M("mod_party_join")->postData($un);
			if(!is_tel($data["telephone"])){
				$this->goAll("手机号码出错啦",1);
			} 
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			$data["shopid"]=$party["shopid"];
			$data["money"]=$party["money"];
			$data["tablename"]=$party["tablename"];
			if($party["retype"]==0 || $data["money"]==0){
				$data["ispay"]=1;
			}
			$joinid=M("mod_party_join")->insert($data);
			$rdata=array(
				"action"=>"finish"			 
			);
			M("mod_party")->update(array(
				"join_num"=>$party["join_num"]+1
			),"id=".$pid);
			if($data["ispay"]){
				
				$this->goall("加入成功，请等待审核");
			}else{
				$_GET["joinid"]=$joinid;
				$res=$this->onPay(true);
				$rdata=array(
					"action"=>"pay"			 
				);
				$rdata['payurl']=$res['payurl'];
				$this->goAll("success",0,$rdata);
			}
			
			
		}
		
		public function onPay($return=0){
			$orderno="Re".M("maxid")->get();
			$joinid=get("joinid",'i');
			$join=M("mod_party_join")->selectRow("id=".$joinid);
			$party=M("mod_party")->selectRow("id=".$join["pid"]);
			if($party["status"]!=1 || $party["isfinish"]){
				$this->goAll("活动已经结束了",1);
			}
			if(strtotime($party["stime"])<time()){
				$this->goAll("活动已经开始了",1);
			}
			$backurl="/module.php?m=party_join&a=success&id=".$joinid;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					M("mod_party_join")->update(array(
						"ispay"=>1,
						"recharge_id"=>"$recharge_id",
					),"id='.$joinid.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$order_product=$orderinfo="支付活动".$party["title"]."参与费用";
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$fromapp=get("fromapp");
			$money= $join['money'];
			$openid=get('openid','h');
			//固定支付
			$rechargeid=M("recharge")->insert(array(
				"orderno"=>$orderno,
				"userid"=>$userid,
				"money"=>$money,
				"pay_type"=>$pay_type,
				"dateline"=>time(),
				"openid"=>$openid,
				"orderinfo"=>$orderinfo, 
				"orderdata"=>$orderdata,
				"status"=>2,
			));
			$bank_type="";
			 
			$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
			$url.="&orderno=".$orderno;
			$url.="&bank_type=".$bank_type;
			$url.="&order_product=".urlencode($order_product);
			$url.="&order_price=".$money;
			$url.="&order_info=".urlencode($order_info);
			$url.="&backurl=".urlencode($backurl);
			$redata=array(
				"payurl"=>$url,
				"action"=>"pay",
				"orderno"=>$orderno
			);
			if($return){
				return $redata;
			}
			//end 固定支付
			$this->goALl("正在前往支付",0,$redata,$url);
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$tablename=get("tablename","h");
			$where=" userid=".$userid." AND status in(0,1,2)";
			if($tablename){
				$where.=" AND tablename='".$tablename."' ";
			}
			$url="/module.php?m=party_join&a=my&tablename=".$tablename;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_party_join")->select($option,$rscount);
			
			if($data){
				$ids=[];
				foreach($data as $v){
					$ids[]=$v["pid"];
				}
				$ps=MM("party","party")->getListByIds($ids);
				foreach($data as $k=>$v){
					
					$data[$k]=$ps[$v["pid"]];
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"tablename"=>$tablename,
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("party_join/my.html");
		}
		
		 
		
		public function onDelete(){
			$pid=get_post('pid',"i");
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$row=M("mod_party_join")->selectRow(" userid=".$userid." AND pid=".$pid);
			if(empty($row) || $row["status"]>1){
				$this->goAll("删除失败",1);
			}
			M("mod_party_join")->update(array("status"=>11)," userid=".$userid." AND pid=".$pid);
			M("mod_party")->changenum("join_num",-1,"id=".$pid);
			$this->goAll("删除成功");
			 
		}
		
		public function onAdmin(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$pid=get("pid","i");
			$party=M("mod_party")->selectRow("id=".$pid);
			if($party["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$where="   pid=".$pid; 
			$type=get("type","h");
			switch($type){
				case "pass":
					$where.=" AND status=1 ";
					break;
				case "forbid":
					$where.=" AND status=2 ";
					break;
				case "unpay":
					$where.=" AND ispay=0 ";
					break;
				default:
					$where.=" AND status=0 ";
					break;
			}
			$list=M("mod_party_join")->select(array(
				"where"=>$where,
				"order"=>"id ASC"
			));
			if(!empty($list)){
				$uids=[];
				$statusList=array(
					0=>"待审核",
					1=>"已通过",
					2=>"未通过"
					
				);
				foreach($list as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($list as $k=>$v){
					 
					$v["user_head"]=$us[$v["userid"]]["user_head"];
					$v["status_name"]=$statusList[$v["status"]];
					$list[$k]=$v;
				}
			}
			$this->smarty->goAssign(array(
				"party"=>$party,
				"list"=>$list,
				"unList"=>$unList
			));
			$this->smarty->display("party_join/admin.html");
		}
		
		
		public function onPass(){
			$id=get("id","i");
			$join=M("mod_party_join")->selectRow("id=".$id);
			if(empty($join)){
				$this->goAll("用户不存在",1);
			}
			if($join["status"]!=0){
				$this->goAll("已处理了",1);
			}
			if($join["ispay"]==0){
				$this->goAll("该用户还未支付",1);
			}
			$party=m("mod_party")->selectRow("id=".$join["pid"]);
			if(empty($party)){
				$this->goAll("活动不存在",1);
			}
			$userid=M("login")->userid;
			if($party["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_party_join")->update(array(
				"status"=>1
			),"id=".$id);
			//消息推送
			M("notice")->add(array(
				"userid"=>$join["userid"],
				"content"=>"您参与的".$party["title"]."活动通过审核了",
				"linkurl"=>array(
					"path"=>"/module.php",
					"m"=>"party",
					"a"=>"show",
					"param"=>"id=".$party["id"]
				)
			));
			$this->goAll("success");
			
		}
		
		public function onForbid(){
			$id=get("id","i");
			$join=M("mod_party_join")->selectRow("id=".$id);
			if(empty($join)){
				$this->goAll("用户不存在",1);
			}
			if($join["status"]!=0){
				$this->goAll("已处理了",1);
			}
			$party=m("mod_party")->selectRow("id=".$join["pid"]);
			if(empty($party)){
				$this->goAll("活动不存在",1);
			}
			$userid=M("login")->userid;
			if($party["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			m("mod_party")->update(array(
				"join_num"=>$party["join_num"]-1
			),"id=".$join["pid"]);
			M("mod_party_join")->update(array(
				"status"=>2
			),"id=".$id);
			//消息推送
			M("notice")->add(array(
				"userid"=>$join["userid"],
				"content"=>"您参与的".$party["title"]."活动未通过审核",
				"linkurl"=>array(
					"path"=>"/module.php",
					"m"=>"party",
					"a"=>"show",
					"param"=>"id=".$party["id"]
				)
			));
			$this->goAll("success");
			
		}
		
	}

?>